<?
$input = $this->staffForm();

if (filter_input(INPUT_POST, "action")) {
    $action = filter_input(INPUT_POST, "action");
    unset($_POST["action"]);
    $input = $_POST;
    $this->$action($input);
    
    $link = repackQuery(array(
        "done" => ""
    ), array(
        "action", "id"
    ));
    
}

?>
<form class="form form-horizontal modal-content" method="post">
    <div class="modal-header"><h3>Добавить мастера <a class="close" data-dismiss="modal">&times;</a></h3></div>
    <div class="modal-body">
        <input type="hidden" name="action" value="addStaff" />

        <!-- Имя -->
        <div class="form-group">
            <label class="col-sm-4">Имя: </label>
            <div class="col-sm-8">
                <input class="form-control" name="name" type="text" value="<?= $input["name"] ?>">
            </div>
        </div>
        
        <!-- Телефон -->
        <div class="form-group">
            <label class="col-sm-4">Телефон: </label>
            <div class="col-sm-8">
                <input class="form-control" name="phone" type="text" value="<?= $input["phone"] ?>">
            </div>
        </div>
        
        <!-- Процент -->
        <div class="form-group">
            <label class="col-sm-4">Процент: </label>
            <div class="col-sm-8">
                <input class="form-control" name="percent" type="text" value="<?= $input["percent"] ?>">
            </div>
        </div>
        
    </div>
    <div class="modal-footer"> <button type="submit" class="btn btn-primary">Сохранить</button></div>
</form>