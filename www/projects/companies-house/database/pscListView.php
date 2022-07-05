<?php
if (isset($pscDetails)) {
    echo "<p>" . count($pscDetails) . " people found</p>";
    echo "<table class='psc-details-table'><tr><th>Name</th><th>Born</th><th>Company number</th></tr>";
    foreach($pscDetails as $pscDetail){
        ?>
        <tr>
            <td><?=$pscDetail["firstName"] . " " . $pscDetail["lastName"]?></td>
            <td><?=$pscDetail["birthYear"]?></td>
            <td><?=$pscDetail["companyNumber"]?></td>
        </tr>
<?php
    }
    echo "</table>";
}else{
    echo "No people found";
}

?>
<style>
    table.psc-details-table{
        min-width: 400px;
        border-collapse: collapse;
    }
    table.psc-details-table>tr{
        border: 1px solid #2F302F;
    }
    table.psc-details-table td{
        border-top: 1px solid #2F302F;
        padding: 5px 20px;
    }
    table.psc-details-table th{
        text-align: left;
        padding: 20px;
        background-color: rgba(245, 245, 245, 0.5);
    }

</style>
