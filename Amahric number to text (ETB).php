class ETBnumToText
{

///////////////////////////   convert decimal number in to text   ///////////////////////////////////////////////////////////////////////////////////
	private function changeToWords($numb, $isCurrency)
	{
		$val = "";
		$wholeNo = $numb;
		$points = "";
		$andStr = "";
		$pointStr="";
		
		$endStr = $isCurrency ? "ብቻ" : ("");
		try
		{
			$decimalPlace = stripos($numb, ".");
			
			if ($decimalPlace > 0)
			{
				$wholeNo = substr($numb, 0, $decimalPlace);
				$points = substr($numb, $decimalPlace+1);
				if ($points > 0)
				{
					$andStr = $isCurrency ? " ከ ": "ነጥብ";// just to separate whole numbers from points/cents
					$endStr = $isCurrency ? "ሳንቲም " . $endStr : "";
					$pointStr = $this->translateWholeNumber($points). " ሳንቲም";
				}
			}
			$val = trim($this->translateWholeNumber($wholeNo) ." ብር ".$andStr.$pointStr);
		}
		catch (Exception $e){ ;}
		return $val;
	}
	
	private function translateWholeNumber($number)
	{
		$word = "";
		try
		{
			$beginsZero = false;//tests for 0XX
			$isDone = false;//test if already translated
			$dblAmt = $number;
			//if ((dblAmt > 0) && number.StartsWith("0"))
			if ($dblAmt > 0)
			{//test for zero or digit zero in a nuemric
				if(substr($number, 0, strlen($number)) === $number){
					$beginsZero = true;
				}
				$numDigits = strlen($number);
				$pos = 0;//store digit grouping
				$place = "";//digit grouping name:hundres,thousand,etc...
				switch ($numDigits)
				{
					case 1://ones' range
					$word = $this->ones($number);
					$isDone = true;
					break;
					case 2://tens' range
					$word = $this->tens($number);
					$isDone = true;
					break;
					case 3://hundreds' range
					$pos = ($numDigits % 3) + 1;
					$place = " መቶ ";
					break;
					case 4://thousands' range
					case 5:
					case 6:
					$pos = ($numDigits % 4) + 1;
					$place = " ሺህ ";
					break;
					case 7://millions' range
					case 8:
					case 9:
					$pos = ($numDigits % 7) + 1;
					$place = " ሚሊዮን ";
					break;
					case 10://Billions's range
					$pos = ($numDigits % 10) + 1;
					$place = " ቢሊዮን ";
					break;
					//add extra case options for anything above Billion...
					default:
					$isDone = true;
					break;
				}
				if (!$isDone)
				{//if transalation is not done, continue...(Recursion comes in now!!)
					$word = $this->translateWholeNumber(substr($number, 0, $pos)) . $place . $this->translateWholeNumber(substr($number, $pos));
					//check for trailing zeros
					if ($beginsZero == true) {
						$word = trim($word);
					}
				}
				//ignore digit grouping names
				if (trim($word) == trim($place)) $word = "";
			}
		}
		catch (Exception $e){ ;}
		return trim($word);
	}
	
	private function tens($digit)
	{
		$digt = $digit;
		$name = null;
		switch ($digt)
		{
			case 10:
			$name = "አስር";
			break;
			case 11:
			$name = "አስራ አንድ";
			break;
			case 12:
			$name = "አስራ ሁለት";
			break;
			case 13:
			$name = "አስራ ሶስት";
			break;
			case 14:
			$name = "አስራ አራት";
			break;
			case 15:
			$name = "አስራ አምስት";
			break;
			case 16:
			$name = "አስራ ስድስት";
			break;
			case 17:
			$name = "አስራ ሰባት";
			break;
			case 18:
			$name = "አስራ ስምንት";
			break;
			case 19:
			$name = "አስራ ዘጠኝ";
			break;
			case 20:
			$name = "ሃያ";
			break;
			case 30:
			$name = "ሰላሳ";
			break;
			case 40:
			$name = "አርባ";
			break;
			case 50:
			$name = "ኃምሳ";
			break;
			case 60:
			$name = "ስልሳ";
			break;
			case 70:
			$name = "ሰባ";
			break;
			case 80:
			$name = "ሰማንያ";
			break;
			case 90:
			$name = "ዘጠና";
			break;
			default:
			if ($digt > 0)
			{
				$name = $this->tens(substr($digit, 0, 1) . "0") . " " . $this->ones(substr($digit, 1));
			}
			break;
		}
		return $name;
	}

	private function ones($digit)
	{
		$digt = $digit;
		$name = "";
		switch ($digt)
		{
			case 1:
			$name = "አንድ";
			break;
			case 2:
			$name = "ሁለት";
			break;
			case 3:
			$name = "ሶስት";
			break;
			case 4:
			$name = "አራት";
			break;
			case 5:
			$name = "አምስት";
			break;
			case 6:
			$name = "ስድስት";
			break;
			case 7:
			$name = "ሰባት";
			break;
			case 8:
			$name = "ስምንት";
			break;
			case 9:
			$name = "ዘጠኝ";
			break;
		}
		return $name;
	}

	private function translateCents($cents)
	{
	$cts = "";
	$digit = "";
	$engOne = "";
	
	for ($i = 0; $i < strlen($cents); $i++)
	{
		$digit = (string)substr($cents, $i, 1);
		if ($digit == 0)
		{
			$engOne = "ዜሮ";
		}
		else
		{
			$engOne = $this->ones($digit);
		}
		$cts .= " " . $engOne;
	}
	return $cts;
	}
}