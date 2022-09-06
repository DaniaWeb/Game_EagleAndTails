<?php
//орел и решка , есть 2 игрока с определеным количеством монет , если выпадает орел то монета достается первому игроку 
//если выпадает решка то второму игроку. игра идет до тех пор пока у одного из игроков не закончатся монеты.

//планы на улучшение: 
// Сделать возможность вводить имена игроков пользователю А также кол во монет. (форма)
// Кнопку отправления формы и старта игры
// Чтоб данные не требовалось вводить каждый раз (сама игра на другой странице)
// запоминание итогов игр, для вывода статистики и сравнивания теоретических расчетов с практическими данными
// возможность регистрации игроков 
class Player{
    public $name;
    public $coins;

    public function __construct($name, $coins)
    {
        $this->name=$name;
        $this->coins=$coins;        
    }
    public function point(Player $player)
    {
        $this->coins++;
        $player->coins--;
    }
    //проигравший тот у кого остается 0 монет
    public function bankrupt()
    {
        return $this->coins == 0;
    }
    // Сумма монет у игрока
    public function bank()
    {
        return $this->coins;
    }
    //шанс победить у игрока = банк игрока / на сумму монет обоих игроков
    public function oods(Player $player)
    {
        return round($this->bank()/($this->bank()+$player->bank())*100, 2) . '%';
    }
}

class Game {
    protected $player1; 
    protected $player2;
    protected $flips = 1;
    
    public function __construct(Player $player1, Player $player2)
    {
        $this->player1=$player1;
        $this->player2=$player2;
    }
    //орел или решка определятся случайно
    public function flip()
    {
        return rand(0,1) ? "орел" : "решка";
    }

    public function start()
    {
        echo $this->player1->name." шансы победить: ".$this->player1->oods($this->player2)."<br>";
        echo $this->player2->name." шансы победить: ".$this->player2->oods($this->player1)."<br>";
        $this->play();
    }

    public function play()
    {
        while(true) {
            //если выпадает орел то монета достается 1 игроку если решка то 2-у игроку
            if($this->flip()=="орел"){
                $this->player1->point($this->player2);
            } else {
                $this->player2->point($this->player1);
            }
            //если у первого или у второго игрока закончились монеты игра заканчивается
            if($this->player1->bankrupt() || $this->player2->bankrupt()) {
                return $this->end();
            }
            $this-> flips++;
        }
        
    }

    public function winner(): Player
    {
        return $this->player1->bank() > $this->player2->bank() ? $this->player1 : $this->player2;
    }

    public function end()
    {
        echo "Game Over"."<br>";
        echo $this->player1->name.":".$this->player1->coins."<br>";
        echo $this->player2->name.":".$this->player2->coins."<br>";
        echo "Выиграл: ".$this->winner()->name."<br>";
        echo 'Кол-во подбрасываний:'. $this->flips."<br>";
    }

}

$game = new Game(
    new Player("Daniil", 1000),
    new Player("Maxim", 100)
);

$game->start();

?>

