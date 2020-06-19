<?php
use Slim\Factory\AppFactory;
use Psr\Http\message\ResponseInterface as Response;
use Psr\Http\message\ServerRequestInterface as Request;


include_once(__DIR__.'\apigroup\pedido\CTRLPedido.php');


require __DIR__ . '/vendor/autoload.php';

$app = AppFactory::create();


$customErrorHandler = function (
    Psr\Http\Message\ServerRequestInterface $request,
    \Throwable $exception,
    bool $displayErrorDetails,
    bool $logErrors,
    bool $logErrorDetails
	) use ($app) {
		$response = $app->getResponseFactory()->createResponse();
	// a partir do slim com a variavel, usar funcao use
			if ($exception instanceof HttpNotFoundException) {
				$message = 'not found';
				$code = 404;
			} elseif ($exception instanceof HttpMethodNotAllowedException) {
				$message = 'not allowed';
				$code = 403;
			}
		$response->getBody()->write($message);
		return $response->withStatus(404);
	};

$errorMiddleware = $app->addErrorMiddleware(true, true, true);
$errorMiddleware->setErrorHandler(Slim\Exception\HttpNotFoundException::class, $customErrorHandler);

$app->group('/api/pedido'
, function($app){
    $app->get('', 'pedidoController:list');
    $app->get('/{idpedido}', 'pedidoController:SearchByidpedido');    
    $app->put('/{idpedido}', 'pedidoController:update');
    $app->post('', 'pedidoController:insert');
    $app->delete('/{idpedido}', 'pedidoController:delete');
});

$app->run();
?>