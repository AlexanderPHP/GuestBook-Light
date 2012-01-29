<?php
class Capcha{
	private
		$Width = 190, 											# Шрирна изображения
		$Height = 45,											# Высота изображения
		$CodeLength = 6,										# Кол-во символов
		$ApproveAlphabet = 'ABCDEFGHKLMNPRSTUVWYZ23456789',   	# Допускаемые символы
		$FontFile = "../data/elephant.ttf",						# Фйл сшифтов
		$FontSize = 24,											# Размер шрифта
		$BackgroundColor = "#f5f5f5", #e3daed					# Цвет фона капчи
		$CapchaColors = "#0a68dd,#f65c47,#8d32fd", 				# Взможные цвета букв. перечислять через запятую в формате #хххххх. минимум 2 цвета
		$TextTransparency = 15,									# Прозрачность техта на капче
		$LinesColor = "#80BFFF",								# Цвет линий перекрывающих капчу
		$LinesDistanse = 7,										# Промежуток между продольными и поперечными линиями капчи
		$PaddingLife = 10,										# Отступ с лува перед написанием текста
		$LineThickness = 1,										# Толщина линий перекрывающих текст
		$AngleMinimum = -40,									# наклон в лево от  (градусов)
		$AngleMaximum = 40,										# наклон влево до (градусов)
		$DistanceMinimum = 25,									# Расстояние между символами от (пикселей)
		$DistanceMaximum = 30,									# Растояние между символами до (пикселей)
		$JpegQuality = 100,										# Качество изображение (от 0 до 100)
		$Capcha,
		$Code;

	public function show(){
			$this->Capcha = imagecreatetruecolor($this->Width, $this->Height);
			$bgcolor = imagecolorallocate($this->Capcha, hexdec(substr($this->BackgroundColor, 1, 2)), hexdec(substr($this->BackgroundColor, 3, 2)), hexdec(substr($this->BackgroundColor, 5, 2)));
			imagefilledrectangle($this->Capcha, 0, 0, imagesx($this->Capcha), imagesy($this->Capcha), $bgcolor);
			$this->GenerateCode();
			$this->drawWord();
			$this->drawLines();
			$this->output();
	}

	private function drawLines() {
			$linecolor = imagecolorallocate(  $this->Capcha, hexdec(substr($this->LinesColor, 1, 2)), hexdec(substr($this->LinesColor, 3, 2)), hexdec(substr($this->LinesColor, 5, 2))  );
			imagesetthickness($this->Capcha, $this->LineThickness);

			for($x = 1; $x < $this->Width; $x += $this->LinesDistanse) {
			  imageline($this->Capcha, $x, 0, $x, $this->Height, $linecolor);
			}

			for($y = 11; $y < $this->Height; $y += $this->LinesDistanse) {
			  imageline($this->Capcha, 0, $y, $this->Width, $y, $linecolor);
			}
	}


	private function drawWord(){

			$alpha = intval($this->TextTransparency / 100 * 127);
			$x = $this->PaddingLife;
			$strlen = strlen($this->Code);
			$y_min = ($this->Height / 2) + ($this->FontSize / 2) - 2;
			$y_max = ($this->Height / 2) + ($this->FontSize / 2) + 2;
			$colors = explode(',', $this->CapchaColors);

			for($i = 0; $i < $strlen; ++$i) {
			$angle = rand($this->AngleMinimum, $this->AngleMaximum);
			$y = rand($y_min, $y_max);

			  $idx = rand(0, sizeof($colors) - 1);
			  $r = substr($colors[$idx], 1, 2);
			  $g = substr($colors[$idx], 3, 2);
			  $b = substr($colors[$idx], 5, 2);
			  $font_color = imagecolorallocatealpha($this->Capcha, "0x$r", "0x$g", "0x$b", $alpha);

			imagettftext($this->Capcha, $this->FontSize, $angle, $x, $y, $font_color, $this->FontFile, $this->Code{$i});

			$x += rand($this->DistanceMinimum, $this->DistanceMaximum);
			} 
	}

	private function GenerateCode(){

			for($i = 1, $cslen = strlen($this->ApproveAlphabet); $i <= $this->CodeLength; ++$i) {
			  $this->Code .= strtoupper( $this->ApproveAlphabet{rand(0, $cslen - 1)} );
			}

			$_SESSION['sec_code_session'] = strtolower($this->Code);
	}

	private function output(){
			header("Expires: Sun, 23 Oct 2008 22:13:00 GMT");
			header("Last-Modified: " . gmdate("D, d M Y H:i:s") . "GMT");
			header("Cache-Control: no-store, no-cache, must-revalidate");
			header("Cache-Control: post-check=0, pre-check=0", false);
			header("Pragma: no-cache");
			header("Content-Type: image/jpeg");
			imagejpeg($this->Capcha, null, $this->JpegQuality);
			imagedestroy($this->Capcha);
	}
}
$i = new Capcha();
$i->Show();
echo $_SESSION['sec_code_session'];
?>