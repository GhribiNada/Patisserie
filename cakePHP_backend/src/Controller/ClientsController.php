<?php
declare(strict_types=1);

namespace App\Controller;
use Cake\View\JsonView;
/**
 * Clients Controller
 *
 * @property \App\Model\Table\ClientsTable $Clients
 * @method \App\Model\Entity\Client[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ClientsController extends AppController
{


    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function viewClasses(): array
    {
        return [JsonView::class];
    }

    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('RequestHandler');
    }

    public function beforeFilter(\Cake\Event\EventInterface $event)
    {
        parent::beforeFilter($event);
        $this->response = $this->response
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, PATCH, DELETE')
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Authorization')
            ->withHeader('Access-Control-Max-Age', '3600');

        if ($this->request->is('options')) {
            return $this->response;
        }
    }

    public function index()
    {
        $clients = $this->paginate($this->Clients);
        $this->set(compact('clients'));
        $this->viewBuilder()->setOption('serialize', ['clients']);
      // echo json_encode($clients);
       // exit();
    }

    public function view($id)
    {
        $client = $this->Clients->get($id);
        $this->set('client', $client);
        $this->viewBuilder()->setOption('serialize', ['client']);
    
    }

    public function add()
    {
        // Vérifiez s'il y a des données postées
        if ($this->request->is('post')) {
            // Créez une nouvelle entité en utilisant les données postées
            $client = $this->Clients->newEntity($this->request->getData());
    
            // Vérifiez si l'entité est valide et sauvegardez-la
            if ($this->Clients->save($client)) {
                $message = 'Saved';
            } else {
                $message = 'Error';
                $errors = $client->getErrors(); // $errors est un tableau
            }
        } else {
            // Si aucune donnée postée, créez simplement une nouvelle entité
            $client = $this->Clients->newEmptyEntity();
        }
    
        // Passez les variables à la vue
        $message = ""; // Initialisez la variable avec une valeur par défaut
        $this->set([
            'message' => $message,
            'client' => $client,
            'errors' => isset($errors) ? $errors : null, // Ajustez le type d'erreur sur array
        ]);
        $this->viewBuilder()->setOption('serialize', ['client', 'message', 'errors']);
    }
    



    public function edit($id)
    {
        $client = $this->Clients->get($id);
        if ($this->request->is(['post', 'put'])) {
            $client = $this->Clients->patchEntity($client, $this->request->getData());
            if ($this->Clients->save($client)) {
                $message = 'Saved';
            } else {
                $message = 'Error';
            }
        }
        $message = ""; 
        $this->set([
            'message' => $message,
            'client' => $client,
        ]);
        $this->viewBuilder()->setOption('serialize', ['client', 'message']);
    }

    public function delete($id)
    {
        try {
            $client = $this->Clients->get($id);
            $message = 'Deleted';
            if (!$this->Clients->delete($client)) {
                $message = 'Error';
            }
        } catch (\Cake\Datasource\Exception\RecordNotFoundException $e) {
            // Gérer le cas où l'entité avec l'ID donné n'existe pas
            $message = 'Error: Record not found';
        }
    
        $this->set('message', $message);
        $this->viewBuilder()->setOption('serialize', ['message']);
    }
    

}
