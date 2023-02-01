<?php

namespace App\Http\Controllers;

use App\Classes\phpari;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Exports\UsersExport;
use App\Http\Resources\UserResource;
use App\Http\src\interfaces\channels;
use Illuminate\Http\Resources\Json\JsonResource;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\HttpException;
use PAMI\Client\Impl\ClientImpl as PamiClient;
use PAMI\Message\Action\OriginateAction;
use PAMI\Message\Action\LogoffAction;
use PAMI\Message\Event\EventMessage;
use PAMI\Listener\IEventListener;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return UserResource::make($users);
    }

    public function show($id)
    {
        $user = User::where('id', $id)
            ->allowedIncludes(['role'])
            ->firstOrFail();
        return UserResource::make($user);
        //return $user;
    }
    public function downloadPdf()
    {
        $users = User::all();

        view()->share('users.pdf', $users);

        $pdf = Pdf::loadView('users.pdf', ['users' => $users]);

        return $pdf->download('users.pdf');
    }

    public function export()
    {
        return Excel::download(new UsersExport, 'users.xlsx');
    }

    public function push() {
        $conn     = new phpari("elfec2"); //create new object
        $channels = new channels($conn);
        $channel = $channels->channel_originate(
            'SIP/4237695',
            45,
            array(
                "extension"      => "500",
                "context" => 'ari-prueba',
            ),

        );
        return $channel? $channel: response()->json(['mesagge'=> 'Error al crear canal'], 200);
    }

    private function generateNotificatioToSend()
    {
        return  [
            "data" => [
                "sendMail" => false,
                "nuses" => ["167254"],
                "notification" => [
                    "title" => "sin titulo",
                    "message" => "sin mensaje"
                ]
            ]
        ];
    }


    public function ctc($numeroA, $numeroB)
    {

        // Configuramos a conexao com o manager do Asterisk
        $pamiClientOptions = array(
            'host' => '192.168.1.116',
            'scheme' => 'tcp://',
            'port' => '5038',
            'username' => 'laravel',
            'secret' => 'laravel',
            'connect_timeout' => 60000,
            'read_timeout' => 60000
        );



$pamiClient = new PamiClient($pamiClientOptions);

// Open the connection
$pamiClient->open();

$pamiClient->registerEventListener(function (EventMessage $event) {
    var_dump($event);
});

$running = true;

// Main loop
while($running) {
    $pamiClient->process();
    usleep(1000);
}
    }

}
