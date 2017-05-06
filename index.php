<html>
<head>
<meta charset="UTF-8">
<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
<meta http-equiv="Pragma" content="no-cache" />
<meta http-equiv="Expires" content="0" />
<title>Ajoneuvohaku</title>

<script type="text/javascript" src="//code.jquery.com/jquery-1.9.1.js"></script>
<style>

thead.borders th {
  border-bottom:1pt solid black;
  border-top:1pt solid black;
}

tr.borders td {
  border-bottom:1pt solid black;
  border-top:1pt solid black;
}

.table-striped tbody tr:nth-child(odd) td {
  background-color: #FFFAF0;
}

.table-striped tbody tr.highlight td { 
    background-color: #ADD8E6;
}

a {
   text-decoration: none;
   color: black;
   font-size: 12px
  }
a.button {
    -webkit-appearance: button;
    -moz-appearance: button;
    appearance: button;

    text-decoration: none;
    color: initial;
}
input {
    width: 400px;
    padding: 0 20px;
}

input,
input::-webkit-input-placeholder {
    font-size: 20px;
    line-height: 3;
}
table {
    border-collapse: collapse;
    border-spacing: 0;
    width: 100%;
    border: 1px solid #ddd;
}

th, td {
    border: none;
    text-align: left;
    padding: 8px;
}

th {border: white}

tr:nth-child(even){background-color: #f2f2f2}

.whiteborder td {
 background: white;
 bgcolor: white
}

</style>
<script type='text/javascript'>
$(window).load(function(){
    $('input,textarea').focus(function(){
       $(this).removeAttr('placeholder');
    });
});
</script>
<script type='text/javascript'>//<![CDATA[
$(window).load(function(){
$('#mytable').on('click', 'tbody tr', function(event) {
    $(this).addClass('highlight').siblings().removeClass('highlight');
});
});//]]> 

</script>

</head>
<body>
<?php

if(isset($_POST['reset']) || !isset($_POST['completedsearch']))
{
        $placeholderCar = "Skoda";
        $placeholderModel = "Felicia 1.3";
        $placeholderDate = "2000";
	unset($_POST['merkkiSelvakielinen']);
	unset($_POST['mallimerkinta']);
	unset($_POST['ensirekisterointipvm']);
}
else
{
        $placeholderCar = "";
        $placeholderModel = "";
        $placeholderDate = "";
}
?>


<form method="post" action="index.php">
<input type="text" value="<?php echo htmlspecialchars($_POST['merkkiSelvakielinen']); ?>" name="merkkiSelvakielinen" placeholder="<?php echo $placeholderCar; ?>" style="width: 300px; height: 50px;" />
<input type="text" value="<?php echo htmlspecialchars($_POST['mallimerkinta']); ?>" name="mallimerkinta" placeholder="<?php echo $placeholderModel; ?>" style="width: 300px; height: 50px;" />
<input type="text" value="<?php echo htmlspecialchars($_POST['ensirekisterointipvm']); ?>" name="ensirekisterointipvm" placeholder="<?php echo $placeholderDate; ?>" style="width: 200px; height: 50px;" />
<input type="submit" value="Hae" name="completedsearch" style="width: 75px; height: 50px;" />
<input type="submit" value="Reset" name="reset" style="width: 100px; height: 50px;" />
</form>
<a href="https://www.trafi.fi/tietopalvelut/avoin_data" target="_blank"><img src="trafi_data.png" border="0" /></a><br />
<a href="https://www.trafi.fi/tietopalvelut/avoin_data" target="_blank">Trafin ajoneuvojen avoin data 4.9 aineistoa</a><br /><br />

<?php
			if(isset($_POST['completedsearch']))
			{
				$searchModel = $_POST['mallimerkinta'];
				$modelKeys = explode(" ",$searchModel);

				if ($_POST['merkkiSelvakielinen'] == "") {exit(); }

				// define the list of fields
    				$fields = array('merkkiSelvakielinen', 'ensirekisterointipvm');
    				$conditions = array();

    				// loop through the defined fields
    				foreach($fields as $field)
				{
        				// if the field is set and not empty
        				if(isset($_POST[$field]) && $_POST[$field] != '') {
            				// create a new condition while escaping the value inputed by the user (SQL Injection)
            				$conditions[] = "$field LIKE '%" . $_POST[$field] . "%'";
        			}
    			}

    			// builds the query
    			$query = "SELECT * FROM tieliikenne_avoin_data ";
    			// if there are conditions defined
    			if(count($conditions) > 0) 
			{
        			// append the conditions
        			$query .= "WHERE " . implode (' AND ', $conditions) ." AND ( ";
				foreach($modelKeys as $key)
				{
    					$query .= " mallimerkinta LIKE '%$key%' AND ";
				}
				$new_query = preg_replace('/AND $/', '', $query);
				$query = $new_query;
				$query .= ") ORDER BY ensirekisterointipvm LIMIT 100"; // you can change to 'OR', but I suggest to apply the filters cumulative
    			}
				//echo $query."<br />";

				//$result = mysql_query($query);
                                $mysql = mysql_connect("localhost","db_user","password");
				mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'", $mysql);
                                mysql_select_db("db_name");
				$result = mysql_query($query);
                                //$qu = mysql_query("SELECT * FROM tieliikenne_avoin_data WHERE ensirekisterointipvm LIKE '%{$regDate}%' AND ( merkkiSelvakielinen LIKE '%{$carName}%' OR mallimerkinta LIKE '%{$searchModel[0]}%' ) ORDER BY ensirekisterointipvm LIMIT 100"); //selects the row that contains ANYTHING like the submitted string
 				echo '<table border="1" id="mytable" class="table-striped"><thead class="borders"><th>Merkki</th><th>Malli</th><th>ensirekisterointipvm</th><th>vari</th><th>ovienLukumaara</th><th>korityyppi</th><th>omamassa</th><th>tieliikSuurSallKokmassa</th><th>ajonKokPituus</th><th>ajonLeveys</th><th>ajonKorkeus</th><th>kayttovoima</th><th>iskutilavuus</th><th>suurinNettoteho</th><th>sylintereidenLkm</th><th>ahdin</th><th>vaihteisto</th><th>tyyppihyvaksyntanro</th><th>matkamittarilukema</th></thead>'."\n";
				$resultCounter = 0;
                                while($row = mysql_fetch_array($result))
                                           {
						$resultCounter++;
						if ($resultCounter % 20 == 0)
						{
							$resultCounter = 0;
							echo '<tr class="borders" style="font-weight:bold"><td bgcolor="white">Merkki</td><td bgcolor="white">Malli</td><td bgcolor="white">ensirekisterointipvm</td><td bgcolor="white">vari</td><td bgcolor="white">ovienLukumaara</td><td bgcolor="white">korityyppi</td><td bgcolor="white">omamassa</td><td bgcolor="white">tieliikSuurSallKokmassa</td><td bgcolor="white">ajonKokPituus</td><td bgcolor="white">ajonLeveys</td><td bgcolor="white">ajonKorkeus</td><td bgcolor="white">kayttovoima</td><td bgcolor="white">iskutilavuus</td><td bgcolor="white">suurinNettoteho</td><td bgcolor="white">sylintereidenLkm</td><td bgcolor="white">ahdin</td><td bgcolor="white">vaihteisto</td><td bgcolor="white">tyyppihyvaksyntanro</td><td bgcolor="white">matkamittarilukema</td></tr>';
						}
                                                echo "<tr>";
						echo '<td nowrap="nowrap">';
                                                echo $row['merkkiSelvakielinen'];
                                                echo "</td>";
                                                echo '<td nowrap="nowrap">';
                                                echo $row['mallimerkinta'];
                                                echo "</td>";
						echo "<td>";
                                                echo $row['ensirekisterointipvm'];
                                                echo "</td>";
                                                echo "<td>";
                                                echo $row['vari'];
                                                echo "</td>";
						echo "<td>";
                                                echo $row['ovienLukumaara'];
                                                echo "</td>";
						echo "<td>";
                                                echo $row['korityyppi'];
                                                echo "</td>";
                                                echo "<td>";
                                                echo $row['omamassa'];
                                                echo "</td>";
                                                echo "<td>";
                                                echo $row['tieliikSuurSallKokmassa'];
                                                echo "</td>";
						echo "<td>";
                                                echo $row['ajonKokPituus'];
                                                echo "</td>";
						echo "<td>";
                                                echo $row['ajonLeveys'];
                                                echo "</td>";
                                                echo "<td>";
                                                echo $row['ajonKorkeus'];
                                                echo "</td>";
                                                echo "<td>";
                                                echo $row['kayttovoima'];
                                                echo "</td>";
						echo "<td>";
                                                echo $row['iskutilavuus'];
                                                echo "</td>";
                                                echo "<td>";
                                                echo $row['suurinNettoteho'];
                                                echo "</td>";
                                                echo "<td>";
                                                echo $row['sylintereidenLkm'];
                                                echo "</td>";
						echo "<td>";
                                                echo $row['ahdin'];
                                                echo "</td>";
						echo "<td>";
                                                echo $row['vaihteisto'];
                                                echo "</td>";
                                                echo "<td>";
                                                echo $row['tyyppihyvaksyntanro'];
                                                echo "</td>";
                                                echo "<td>";
                                                echo $row['matkamittarilukema'];
                                                echo "</td>";
						echo "</tr>\n";

                                }
				echo "</table><br />";
				$resultNum = mysql_num_rows($result);
				if ($resultNum < 100 )
				{ echo "Hakutuloksia ". $resultNum ." riviä";}
				else
				{ echo "Hakutuloksia yli ". $resultNum ." riviä";}

                        }
                ?>
</body>
</html>
