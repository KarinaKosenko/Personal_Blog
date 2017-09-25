<div id="main_column">
    <div>
        <?=$msg;?>
    </div>
    <br>
    <form method="get">
        <?php
        $months = ["Январь" => 1, "Февраль" => 2, "Март" => 3, "Апрель" => 4, 
                   "Май" => 5, "Июнь" => 6, "Июль" => 7, "Август" => 8,
                   "Сентябрь" => 9, "Октябрь" => 10, "Ноябрь" => 11, "Декабрь" => 12];
        ?>
            
            <p><strong>Год:</strong></p>
            <p><select name="year">
                <?php
                    $this_year = date('Y');
                    for($year = 2017; $year <= $this_year; $year++){
                        echo "<option value=\"$year\">$year</option>";
                    }
                ?>
           </select><br>
           <p><strong>Месяц:</strong></p>
            <p><select name="month">
                <?php
                    foreach($months as $name => $number){
                        echo "<option value=\"$number\">$name</option>";
                    }
                ?>
           </select><br>
            <input type="submit" value="Отправить"><br>
    </form>
    
</div>
