<?
/**
*	
*/
class Model
{
	protected function getList()
	{
		// Здесь реализовать получение из БД
		return [
			['name' => 'ser','text'=>'СанЭпидРежим'],
			['name' => 'feld','text'=>'Фельдшеры'],
			['name' => 'smp','text'=>'Скорая помощь'],
            ['name' => 'help','text'=>'Инструкция']
		];
	}

	public function renderHead(){}
    public function renderBody(){}

}