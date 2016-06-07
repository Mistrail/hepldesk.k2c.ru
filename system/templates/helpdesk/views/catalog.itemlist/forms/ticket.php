<?
$input = $this->ticketForm();
$action = filter_input(INPUT_POST, "action");

if ($action == "addTicket") {
    unset($_POST["action"]);
    $input = $_POST;
    $input["date"] = implode(" ", $input["date"]) . ":00";
    $input["date"] = strtotime($input["date"]);
    $input["date"] = date("Y-m-d H:i:s", $input["date"]);

    $input["payout_date_real"] = implode(" ", $input["payout_date_real"]);
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

$input["phone"] = !empty($_GET["phone"]) ? $_GET["phone"] : $input["phone"];
?>
<form class="form form-horizontal modal-content" method="post">
    <div class="modal-header"><h3>Форма заявки <a class="close" data-dismiss="modal">&times;</a></h3></div>
    <div class="modal-body">
        <input type="hidden" name="action" value="addTicket" />

        <!-- ID заявки -->
        <div class="form-group">
            <label class="col-sm-4">ID заявки: </label>
            <div class="col-sm-2">
                <input class="form-control" readonly="readonly" type="text" value="<?= $this->getNextID(); ?>">
            </div>    
        </div>

        <!-- Статус -->
        <div class="form-group">
            <label class="col-sm-4">Статус: </label>
            <div class="col-sm-8">
                <select class="form-control" name="status_id">
                    <?
                    $optionsObj = $this->db->query("SELECT * FROM @px:statustypes");
                    while ($option = $optionsObj->getRow()):
                        $selected = $option["id"] == $input["status_id"] ? "selected=\"selected\"" : "";
                        ?>
                        <option value="<?= $option["id"] ?>" <?= $selected ?>><?= $option["title"] ?></option>
                    <? endwhile; ?>
                </select>
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
                    <?
                    for ($i = 9, $j = 10; $i <= 21; $i++, $j++):
                        $str = $i < 10 ? "0$i" : $i;
                        $str2 = $j < 10 ? "0$j" : $j;
                        $selected = $hour == $str ? "selected=\"selected\"" : "";
                        ?>
                        <option <?= $selected ?> value="<?= $str ?>:00"><?= "$str-$str2"; ?></option>
                    <? endfor ?>
                </select>
            </div>
        </div>

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

        <!-- Проблема -->
        <div class="form-group">
            <label class="col-sm-4">Проблема: </label>
            <div class="col-sm-8">
                <textarea class="form-control" name="subject"><?= $input["subject"] ?></textarea>
            </div>
        </div>

        <!-- Тип оплаты -->
        <div class="form-group">
            <label class="col-sm-4">Тип оплаты: </label>
            <div class="col-sm-8">
                <select class="form-control" name="payment_type_id">
                    <?
                    $optionsObj = $this->db->query("SELECT * FROM @px:paymenttypes");
                    while ($option = $optionsObj->getRow()):
                        $selected = $option["id"] == $input["payment_type_id"] ? "selected=\"selected\"" : "";
                        ?>
                        <option value="<?= $option["id"] ?>" <?= $selected ?>><?= $option["title"] ?></option>
                    <? endwhile; ?>
                </select>
            </div>
        </div>

        <!-- Комментарии -->
        <div class="form-group">
            <label class="col-sm-4">Комментарии: </label>
            <div class="col-sm-8">
                <textarea class="form-control" name="comments"><?= $input["comments"] ?></textarea>
            </div>
        </div>

        <!-- Мастер -->
        <div class="form-group">
            <label class="col-sm-4">Мастер: </label>
            <div class="col-sm-8">
                <select class="form-control" name="staff_id">
                    <?
                    $optionsObj = $this->db->query("SELECT * FROM @px:stafflist");
                    while ($option = $optionsObj->getRow()):
                        $selected = (int) $option["id"] == (int) $input["staff_id"] ? "selected=\"selected\"" : "";
                        ?>
                        <option value="<?= $option["id"] ?>" <?= $selected ?>><?= $option["name"] ?></option>
                    <? endwhile; ?>
                </select>
            </div>
        </div>
        <script type="text/javascript">
            $("[name=staff_id]").change(function () {
                var item = $(this);
                $.ajax({
                    url: "/ajax.php?autologin=callcenter",
                    data: {
                        action: "getPercent",
                        module: "catalog",
                        controller: "itemlist",
                        id: item.val(),
                    },
                    type: "post",
                    dataType: "text",
                    success: function (resp) {
                        var target = $("input[name=percent]");
                        target.val(resp);
                        calcSumm();
                    }
                })
            });
            

            function calcSumm() {
                var summ = $("input[name=summ]").val() * 1;
                var summ_zch = $("input[name=summ_zch]").val() * 1;
                var percent = $("input[name=percent]").val() * 1;
                var total = 0;

                var caseType = percent > 0 ? "by_percent" : "no_percent";

                if (caseType == "no_percent") {
                    if (summ > 0 && summ <= 1499) {
                        percent = 15;
                    } else if (summ > 1499 && summ <= 2000) {
                        percent = 20;
                    } else if (summ > 2000 && summ <= 2499) {
                        percent = 25;
                    } else if (summ > 2499 && summ <= 2999) {
                        percent = 30;
                    } else if (summ > 2999 && summ <= 8999) {
                        percent = 35;
                    } else {
                        percent = 45;
                    }
                }

                total = (summ - summ_zch) * (percent / 100);
                
                var target = $("[name=payout]");
                target.val(total);

            }
        </script>

        <!-- Сумма -->
        <div class="form-group">
            <label class="col-sm-4">Сумма: </label>
            <div class="col-sm-8">
                <input onchange="calcSumm()" class="form-control" name="summ" type="text" value="<?= $input["summ"] ?>">
            </div>
        </div>

        <!-- Сумма ЗЧ -->
        <div class="form-group">
            <label class="col-sm-4">Сумма ЗЧ: </label>
            <div class="col-sm-8">
                <input onchange="calcSumm()" class="form-control" name="summ_zch" type="text"  value="<?= $input["summ_zch"] ?>">
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
          <?/* */ ?>

        <!-- Оплачено -->
        <div class="form-group">
            <label class="col-sm-4">Оплачено: </label>
            <div class="col-sm-8">
                <input class="form-control" name="payment" type="text" value="<?= $input["payment"] ?>">
            </div>
        </div>

        <!-- Долг -->
        <div class="form-group">
            <label class="col-sm-4">Долг: </label>
            <div class="col-sm-8">
                <input class="form-control" name="debt" type="text" value="<?= $input["debt"] ?>">
            </div>
        </div>

        <!-- БСО -->
        <div class="form-group">
            <label class="col-sm-4">БСО: </label>
            <div class="col-sm-8">
                <input class="form-control" name="bso" type="text" value="<?= $input["bso"] ?>">
            </div>
        </div>

        <!-- Процент -->
        <div class="form-group">
            <label class="col-sm-4">Процент: </label>
            <div class="col-sm-8">
                <input class="form-control" name="percent" type="text" value="<?= $input["percent"] ?>">
            </div>
        </div>

        <!-- ЗП мастера -->
        <div class="form-group">
            <label class="col-sm-4">ЗП мастера: </label>
            <div class="col-sm-8">
                <input class="form-control" readonly="readonly" name="payout" type="text" value="<?= $input["payout"] ?>">
            </div>
        </div>
        <!-- Дата выплаты -->
        <div class="form-group">
            <label class="col-sm-4">Дата выплаты: </label>
            <div class="col-sm-8">
                <input class="form-control" name="payout_date" type="text" value="<?= $input["payout_date"] ?>">
            </div>
        </div>

        <!-- СД -->
        <? /* ?>
          <div class="form-group">
          <label class="col-sm-4">СД: </label>
          <div class="col-sm-8">
          <input class="form-control" name="sd" type="text" value="<?= $input["sd"] ?>">
          </div>
          </div>
          <?/* */ ?>

    </div>
    <div class="modal-footer"> <button type="submit" class="btn btn-primary">Сохранить</button></div>
</form>