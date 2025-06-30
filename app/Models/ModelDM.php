<?php

namespace App\Models;

use Mongodb\Client;
use MongoDB\Collection;

abstract class ModelDM
{
    protected Client $client;
    protected Collection $collection;
    protected string $database_name = MDB_NAME;
    protected string $collection_name;

    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->collection = $this->client->selectCollection(databaseName: $this->database_name, collectionName: $this->collection_name);
    }


    // CRUD

    /**
     * Create a document
     * 
     * @param mixed $data
     * @return void
     */
    public function createDocument($data)
    {
        $this->collection->insertOne($data);
    }
    // $modelDM = new modelDM();
    // $newDoc = ['title' => 'My title', 'content' => 'This is the content.'];
    // $modelDM->createDocument($newDoc);

    /**
     * Read documents
     * 
     * @param mixed $filter
     * @return \MongoDB\Driver\CursorInterface
     */
    public function readDocument($filter)
    {
        return $this->collection->find($filter);
    }
    // $modelDM = new modelDM();
    // $docs = $modelDM->readDocument([]);
    // foreach ($docs as $doc) {// Process and display document data}

    /**
     * Update a document
     * 
     * @param mixed $filter
     * @param mixed $updateData
     * @return void
     */
    public function updateDocument($filter, $updateData)
    {
        $this->collection->updateOne($filter, ['$set' => $updateData]);
    }
    // $modelDM = new modelDM();
    // $updtFilter = ['title' => 'My New Title'];
    // $updtData = ['content' => 'Updated content'];
    // $modelDM->updateDocument($updtFilter, $updtData);

    /**
     * Delete a document
     * 
     * @param mixed $filter
     * @return void
     */
    public function deleteDocument($filter)
    {
        $this->collection->deleteOne($filter);
    }
    // $modelDM = new modelDM();
    // $delFilter = ['title' => 'My Title'];
    // $modelDM->deleteDocument($delFilter);

}
