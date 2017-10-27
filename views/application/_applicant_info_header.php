<?php

/* @var $user The user model */

?>
<div class="well well-sm">
    <div class="row item">
        <div class="col-sm-6">
            <h2><?= $user->firstname ?> <small>(Όνομα)</small></h2>
        </div>
        <div class="col-sm-6">
            <h2><?= $user->lastname ?> <small>(Επώνυμο)</small></h2>
        </div>
    </div>
    <div class="row item">
        <div class="col-sm-6">
            <h2><?= $user->fathername ?> <small>(Πατρώνυμο)</small></h2>
        </div>
        <div class="col-sm-6">
            <h2><?= $user->email ?> <small>(Email)</small></h2>
        </div>
    </div>
    <div class="row item">
        <div class="col-sm-4">
            <h2><?= $user->specialty ?> <small>(Ειδικότητα)</small></h2>
        </div>
        <div class="col-sm-4">
            <h2><?= $user->vat ?> <small>(Α.Φ.Μ.)</small></h2>
        </div>
        <div class="col-sm-4">
            <h2><?= $user->identity ?> <small>(Ταυτότητα)</small></h2>
        </div>
    </div>
</div>
