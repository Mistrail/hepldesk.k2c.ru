<?
$input = $this->getTicket($_GET["id"]);
$action = filter_input(INPUT_POST, "action");

if ($action == "editTicket") {
    unset($_POST["action"]);

    $input = $_POST;
    $input["date"] = implode(" ", $input["date"]) . ":00";
    $input["date"] = strtotime($input["date"]);
    $input["date"] = date("Y-m-d H:i:s", $input["date"]);

    $input["payout_date_real"] = implode(" ", $input["payout_date_real"]) . ":00";
    $input["payout_date_real"] = strtotime($input["payout_date_real"]);
    $input["payout_date_real"] = date("Y-m-d H:i:s", $input["payout_date_real"]);

    $this->$action($input);

    $link = repackQuery(array(
        "done" => ""
            ), array(
        "action", "id"
    ));
    
}

$input["date"] = $input["date"] ? $input["date"] : date("d.m.Y H:i:s");
$input["payout_date_real"] = $input["payout_date_real"] ? $input["payout_date_real"] : date("d.m.Y H:i:s");

$str_date = strtotime($input["date"]);
$input["date"] = array(
    "date" => date("d.m.Y", $str_date),
    "time" => date("H:i", $str_date),
);

$str_date = strtotime($input["payout_date_real"]);
$input["payout_date_real"] = array(
    "date" => date("d.m.Y", $str_date),
    "time" => date("H:i", $str_date),
);


?>
<form class="form form-horizontal modal-content" method="post">
    <div class="modal-header"><h3>Форма заявки <a class="close" data-dismiss="modal">&times;</a></h3></div>
    <div class="modal-body">
        <input type="hidden" name="action" value="editTicket" />
        <input type="hidden" name="staff_id" value="0" />        
        
        <!-- ID заявки -->
        <div class="form-group">
            <label class="col-sm-4">ID заявки: </label>
            <div class="col-sm-2">
                <input class="form-control" name="id" readonly="readonly" type="text" value="<?=$input["id"]?>">
            </div>    
        </div>
        
        <!-- Дата звонка -->
        <div class="form-group">
            <label class="col-sm-4">Дата звонка: </label>
            <div class="col-sm-2">
                <input class="form-control" name="date[date]" readonly="readonly" type="text" value="<?= $input["date"]["date"] ?>">
            </div>
            <div class="col-sm-2">
                <input class="form-control" name="date[time]" readonly="readonly" type="text" value="<?= $input["date"]["time"] ?>">
            </div>        
        </div>

        <!-- Населенный пункт -->
        <div class="form-group">
            <label class="col-sm-4">Населенный пункт: </label>
            <div class="col-sm-8">
                <input class="form-control" name="city" type="text" value="<?= $input["city"] ?>">
            </div>
        </div>

        <!-- Адрес -->
        <div class="form-group">
            <label class="col-sm-4">Адрес: </label>
            <div class="col-sm-8">
                <input class="form-control" name="address" type="text" value="<?= $input["address"] ?>">
            </div>
        </div>

        <!-- Тип заявки -->
        <div class="form-group">
            <label class="col-sm-4">Тип заявки: </label>
            <div class="col-sm-8">
                <select class="form-control" name="type_id">
                    <?
                    $optionsObj = $this->db->query("SELECT * FROM @px:ordertypes");
                    while ($option = $optionsObj->getRow()):
                        $selected = $option["id"] == $input["type_id"] ? "selected=\"selected\"" : "";
                        ?>
                        <option value="<?= $option["id"] ?>" <?= $selected ?>><?= $option["title"] ?></option>
                    <? endwhile; ?>
                </select>
            </div>
        </div>

        <!-- Источник -->
        <? /* ?>
        <div class="form-group">
            <label class="col-sm-4">Источник: </label>
            <div class="col-sm-8">
                <select class="form-control" name="source_id">
                    <?
                    $optionsObj = $this->db->query("SELECT * FROM @px:soursetypes");
                    while ($option = $optionsObj->getRow()):
                        $selected = $option["id"] == $input["source_id"] ? "selected=\"selected\"" : "";
                        ?>
                        <option value="<?= $option["id"] ?>" <?= $selected ?>><?= $option["title"] ?></option>
                    <? endwhile; ?>
                </select>
            </div>
        </div>
        <?/* */?>
        
        <!-- Контактное лицо -->
        <div class="form-group">
            <label class="col-sm-4">Контактное лицо: </label>
            <div class="col-sm-8">
                <input class="form-control" name="contact" type="text" value="<?= $input["contact"] ?>">
            </div>
        </div>

        <!-- Телефон -->
        <div class="form-group">
            <label class="col-sm-4">Телефон: </label>
            <div class="col-sm-8">
                <input class="form-control" name="phone"  id="phone" type="text" value="<?= $input["phone"] ?>">
            </div>
        </div>

        <!-- Проблема -->
        <div class="form-group">
            <label class="col-sm-4">Проблема: </label>
            <div class="col-sm-8">
                <textarea class="form-control" name="subject"><?= $input["subject"] ?></textarea>
            </div>
        </div>

        <!-- Комментарии -->
        <div class="form-group">
            <label class="col-sm-4">Комментарии: </label>
            <div class="col-sm-8">
                <textarea class="form-control" name="comments"><?= $input["comments"] ?></textarea>
            </div>
        </div>

        <!-- Время заявки -->
        <div class="form-group">
            <label class="col-sm-4">Время заявки: </label>
            <div class="col-sm-2">
                <input id="xdatepicker" name="payout_date_real[date]" type="hidden" value="<?= $input["payout_date_real"]["date"] ?>">
                <script type="text/javascript">
                    $(function () {
                        $('#datepicker').datetimepicker({
                            format: 'DD.mm.YYYY',
                            pickTime: false
                        });
                    });
                </script>
            </div>
            <div class="col-sm-2">
                <?
                $t = strtotime($input["payout_date_real"]["time"]);
                $hour = date("H", $t);
                //var_dump($input["payout_date_real"]);
                ?>
                <select class="form-control" name="payout_date_real[time]">
                    <? for($i = 9, $j = 10; $i <= 21; $i++, $j++): 
                        $str = $i < 10 ? "0$i" : $i;
                        $str2 = $j < 10 ? "0$j" : $j;
                        $selected = $hour == $str ? "selected=\"selected\"" : "";
                        ?>
                    <option <?=$selected?> value="<?=$str?>:00"><?="$str-$str2";?></option>
                    <? endfor?>
                </select>
            </div>
        </div>

    </div>
    <div class="modal-footer"> <button type="submit" class="btn btn-primary">Сохранить</button></div>
</form>