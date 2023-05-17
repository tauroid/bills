<?php

namespace App\Repositories\Transactions;

class UserCantEditException extends \Exception {
    function __construct(UserCantEdit $userCantEdit) {
        $datapaths = $userCantEdit->getDatapaths();

        $datapathStrings = array_map(
            fn ($datapath) =>
            is_array($datapath) ? implode('.', $datapath) : $datapath,
            $datapaths);

        parent::__construct(
            'User (id='.$userCantEdit->getUser()->name.') doesn\'t'
            .' have permission to edit the following fields of'
            .' transaction (id='.$userCantEdit->getTransaction()->id
            .'): '.implode(', ', $datapathStrings));
    }
}
