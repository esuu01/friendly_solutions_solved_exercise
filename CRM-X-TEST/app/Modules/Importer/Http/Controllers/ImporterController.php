<?php

namespace App\Modules\Importer\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Modules\Importer\Models\Importer;
use App\Modules\Importer\Repositories\ImporterRepository;
use App\Modules\WorkOrder\Models\WorkOrder;
use AWS\CRT\Log;
use Carbon\Carbon;
use Carbon\Traits\Date;
use Illuminate\Config\Repository as Config;
use App\Modules\Importer\Http\Requests\ImporterRequest;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Response;
use App;
use Illuminate\Support\Facades\DB;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class ImporterController
 *
 * @package App\Modules\Importer\Http\Controllers
 */
class ImporterController extends Controller
{
    /**
     * Importer repository
     *
     * @var ImporterRepository
     */
    private $importerRepository;

    /**
     * Set repository and apply auth filter
     *
     * @param ImporterRepository $importerRepository
     */
    public function __construct(ImporterRepository $importerRepository)
    {
        //$this->middleware('auth');
        $this->importerRepository = $importerRepository;
    }

    /**
     * Return list of Importer
     *
     * @param Config $config
     *
     * @return Response
     */
    public function index(Config $config)
    {
        //$this->checkPermissions(['importer.index']);
        //$onPage = $config->get('system_settings.importer_pagination');
        //$list = $this->importerRepository->paginate($onPage);

        return view("importer.index");
    }

    /**
     * Return list of Importer log
     *
     * @param Config $config
     *
     * @return Application|Factory|View
     */
    public function logs(Config $config)
    {
        $logs = Importer::all()->sortDesc();

        return view("importer.logs", ["logs" => $logs]);
    }

    /**
     * Display the specified Importer
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $this->checkPermissions(['importer.show']);
        $id = (int) $id;

        return response()->json($this->importerRepository->show($id));
    }

    /**
     * Return module configuration for store action
     *
     * @return Response
     */
    public function create()
    {
        //$this->checkPermissions(['importer.store']);
        $rules['fields'] = $this->importerRepository->getRequestRules();

        return response()->json($rules);
    }

    /**
     * Store a newly created Importer in storage.
     * I used here 3rd party packed called symfony/dom-crawler
     *
     * @param ImporterRequest $request
     *
     */
    public function store(ImporterRequest $request)
    {
        $result = $this->parser($request->file("work_order"), "web");

        // Returning CSV report
        $f = fopen('php://memory', 'w');

        fputcsv($f, array("Key", "Value"));
        fputcsv($f, array("type", "web"));
        fputcsv($f, array("run_at", Carbon::now()));
        fputcsv($f, array("entries_processed", $result["stats"]["processed"]));
        fputcsv($f, array("entries_created", $result["stats"]["created"]));

        if ($result["stats"]["created"] == 0) {
            fputcsv($f, array("note", "Everything skipped because it is already in db."));
        } elseif ($result["stats"]["created"] != $result["stats"]["processed"]) {
            fputcsv($f, array("note", "Few things is already in db. They are skipped."));
        } else {
            fputcsv($f, array("note", "Everything inserted to db"));
        }

        fseek($f, 0);
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="imported_data_report.csv";');
        fpassthru($f);
    }

    static public function parser($file, $type) {
        $result = [
            "data",
            "stats" => [
                "created" => 0,
                "processed" => 0,
            ]
        ];

        $data = new Crawler(file_get_contents($file));

        $result["data"] = $data->filter('#ctl00_ctl00_ContentPlaceHolderMain_MainContent_TicketLists_AllTickets_ctl00 > tbody > tr')->each(function (Crawler $node, $i) {
            return $node->filter("td")->each(function (Crawler $element, $i) {
                if ($i == 0) {
                    return [
                        "link" => explode("entityid=", $element->filter("a")->attr("href"))[1],
                        "id" => $element->text()
                    ];
                }

                return $element->text();
            });
        });

        foreach ($result["data"] as $element) {
            if (count(WorkOrder::where("work_order_number", $element[0]["id"])->get()) == 0) {
                DB::table("work_order")->insert([
                    "work_order_number" => $element[0]["id"],
                    "external_id" => $element[0]["link"],
                    "priority" => $element[3],
                    "received_date" => $element[4],
                    "category" => $element[8],
                    "fin_loc" => $element[10],
                ]);

                $result["stats"]["created"] += 1;
            }

            $result["stats"]["processed"] += 1;
        }

        try {
            Importer::create([
                "type" => $type,
                "entries_processed" => $result["stats"]["processed"],
                "entries_created" => $result["stats"]["created"],
            ]);
        } catch (\Exception $e) {}

        return $result;
    }

    /**
     * Display Importer and module configuration for update action
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $this->checkPermissions(['importer.update']);
        $id = (int) $id;

        return response()->json($this->importerRepository->show($id, true));
    }

    /**
     * Update the specified Importer in storage.
     *
     * @param ImporterRequest $request
     * @param  int $id
     *
     * @return Response
     */
    public function update(ImporterRequest $request, $id)
    {
        $this->checkPermissions(['importer.update']);
        $id = (int) $id;

        $record = $this->importerRepository->updateWithIdAndInput($id,
            $request->all());

        return response()->json(['item' => $record]);
    }

    /**
     * Remove the specified Importer from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $this->checkPermissions(['importer.destroy']);
        App::abort(404);
        exit;

        /* $id = (int) $id;
        $this->importerRepository->destroy($id); */
    }
}
