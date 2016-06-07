<?
$data = $this->getData();

?>

<table class="table table-striped">
     <tr><th>Телефон</th><th>Количество заявок</th></tr>
    <? foreach($data as $row):
        $href = repackQuery(array("s" => $row["phone"], "route" => "catalog/itemlist"));
            ?>
     <tr><td><a href="?<?=$href?>"><?=$row["phone"]?></a></td><td><?=$row["cnt"]?></td></tr>
    <? endforeach; ?>
</table>