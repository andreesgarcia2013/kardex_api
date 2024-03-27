<?php

namespace App\Http\Controllers;

use App\Http\Responses\JsonResponse;
use App\Models\Card;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use ZipArchive;

class CardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(['message' => 'Hola Mundo'], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $numCards = $request->input('num_cards');
            $currentTimestamp = Carbon::now();
            $cards = [];
            for ($i = 0; $i < $numCards; $i++) {
                $cards[] = [
                    'activo_sn' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            
            DB::table('cards')->insert($cards);

            $cards = DB::table('cards')->get();


            $zip = new ZipArchive();
            $zipFileName = 'sample.zip';

            if ($zip->open($zipFileName, ZipArchive::CREATE) === TRUE) {
                foreach ($cards as $card) {
                    // Generar el QR code como una imagen y agregarlo al archivo ZIP
                    $qrCode = QrCode::format('png')->size(300)->generate('http://google.com'); // Reemplaza 'http://example.com' con la URL deseada
                    $qrFileName = "qr_code_$card->id.png";
                    $zip->addFromString($qrFileName, $qrCode);
                }
                $zip->close();

                return response()->download(public_path($zipFileName))->deleteFileAfterSend(true);
            } else {
                return "Failed to create the zip file.";
            }

            // DB::commit();
            // return response()->download($zipFilePath)->deleteFileAfterSend();

        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 200);

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
