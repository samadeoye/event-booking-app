<?php
function getJsonRow($status, $msg, $extraData=[])
{
  $response['status'] = $status;
  $response['msg'] = $msg;

  $returnType = 'json';
  if (count($extraData) > 0)
  {
    if (array_key_exists('returnType', $extraData))
    {
      $returnType = $extraData['returnType'];
    }
    foreach($extraData as $key => $value)
    {
      $response[$key] = $value;
    }
  }
  $response = getJsonList($response, $returnType);
  if ($returnType == 'array')
  {
    return $response;
  }
}
function getJsonList($row, $returnType='json')
{
  if (count($row) > 0)
  {
    if ($returnType == 'json')
    {
      echo json_encode($row, JSON_PRETTY_PRINT);
      exit;
    }
    else
    {
      //array
      return $row;
    }
  }
}

function stripTags($text)
{
	return strip_tags(trim($text));
}

function doTypeCastDouble($number)
{
  return doubleval($number);
}

function doNumberFormat($number)
{
  return number_format($number, 2);
}

function doTypeCastInt($number)
{
  return intval($number);
}

function getCurrencyAmount($amount)
{
  return DEF_CURRENCY_SYMBOL . doNumberFormat($amount);
}

function getUniqIdUpper()
{
  return strtoupper(uniqid());
}

function doValidateRequestParams($data, $throwMsg=false)
{
  $arResponse = [];
  if (count($data) > 0)
  {
    foreach($data as $key => $val)
    {
      $validate = doCheckParamIssetEmpty($key, $val);
      if (!$validate['status'])
      {
        $arResponse = getJsonRow(
          false, $validate['msg'], ['returnType' => 'array']
        );
        break;
      }
    }
  }
  if ($throwMsg)
  {
    if (count($arResponse) > 0)
    {
      if ($arResponse['status'] == false)
      {
        getJsonList($arResponse);
      }
    }
  }
  else
  {
    return $arResponse;
  }
}

function doCheckParamIssetEmpty($param, $data)
{
  $datax = [
    'status' => true,
    'msg' => ''
  ];
  
  $method = $data['method'];
  $label = $data['label'];
  $length = isset($data['length']) ? $data['length'] : [0,0];
  $required = isset($data['required']) ? $data['required'] : false;
  $type = isset($data['type']) ? $data['type'] : "";
  $isEmail = isset($data['is_email']) ? $data['is_email'] : false;

  if(empty($label))
  {
    $label = $param;
  }
  if(strtolower($method) == 'post')
  {
    $isset = isset($_POST[$param]);
    $value = isset($_POST[$param]) ? $_POST[$param] : "";
  }
  elseif(strtolower($method) == 'get')
  {
    $isset = isset($_GET[$param]);
    $value = $isset ? $_GET[$param] : "";
  }
  else
  {
    $isset = isset($_REQUEST[$param]);
    $value = $isset ? $_REQUEST[$param] : "";
  }
  
  if($required)
  {
    $isset = $isset && !empty($value);
    if(!$isset)
    {
      $datax['status'] = false;
      $datax['msg'] = $label . ' is required.';
      return $datax;
    }
  }
  if(!empty($type) && !empty($value))
  {
    if($type == 'string')
    {
      if(!is_string($value))
      {
        $datax['status'] = false;
        $datax['msg'] = $label . ' must be a string.';
        return $datax;
      }
    }
    elseif($type == 'number')
    {
      if(!is_numeric($value))
      {
        $datax['status'] = false;
        $datax['msg'] = $label . ' must contain only digits.';
        return $datax;
      }
    }
  }
  if((!empty($value) && $isEmail) || (!empty($value) && trim($param) == 'email'))
  {
    if(!filter_var($value, FILTER_VALIDATE_EMAIL))
    {
      $datax['status'] = false;
      $datax['msg'] = $label . ' must contain a valid email.';
      return $datax;
    }
  }
  if($length[0] > 0 && $length[1] > 0 && $length[0] == $length[1] && !empty($value))
  {
    $isset = $isset && strlen($value) == $length[0];
    if(!$isset)
    {
      $datax['status'] = false;
      if(strpos($param, '_id') !== false || $param == 'id')
      {
        $datax['msg'] = $label . ' in invalid.';
      }
      else
      {
        $datax['msg'] = $label . ' must be equal to ' . $length[0] .' characters.';
      }
      return $datax;
    }
  }
  if($length[0] > 0 && !empty($value))
  {
      $isset = $isset && strlen($value) >= $length[0];
      if(!$isset)
      {
        $datax['status'] = false;
        if(strpos($param, '_id') !== false || $param == 'id')
        {
          $datax['msg'] = $label . ' in invalid.';
        }
        else
        {
          $datax['msg'] = $label . ' must be greater than or equal to ' . $length[0] .' characters.';
        }
        return $datax;
      }
  }
  if($length[1] > 0 && !empty($value))
  {
    $isset = $isset && strlen($value) <= $length[1];
    if(!$isset)
    {
      $datax['status'] = false;
      if(strpos($param, '_id') !== false || $param == 'id')
      {
        $datax['msg'] = $label . ' in invalid.';
      }
      else
      {
        $datax['msg'] = $label . ' must be less than or equal to ' . $length[1] .' characters.';
      }
      return $datax;
    }
  }
  return $datax;
}

function getNewId()
{
  mt_srand((int)microtime()*10000);
  $charId = strtoupper(md5(uniqid(rand(), true)));
  $hyphen = chr(45);
  $id = substr($charId, 0, 8).$hyphen
  .substr($charId, 8, 4).$hyphen
  .substr($charId, 12, 4).$hyphen
  .substr($charId, 16, 4).$hyphen
  .substr($charId, 20, 12);
  return $id;
}

function getFormattedDate($date, $format='')
{
  if ($date != '')
  {
    if (strlen($date) == 10)
    {
      $format = !empty($format) ? $format : 'Y-m-d H:i';
      return date($format, $date);
    }
  }
  return '';
}

function regexReplace($text)
{
  $text = trim($text);
  return preg_replace( "/[^\.\-\_\@a-zA-Z0-9]/", "", $text );
}

function regexReplaceWithSpace($text)
{
  $text = trim($text);
  return preg_replace( "/[^\.\-\' a-zA-Z0-9]/", "", $text );
}

function regexReplaceMsg($text)
{
  $text = trim($text);
  return preg_replace( "/(From:|To:|BCC:|CC:|Subject:|Content-Type:)/", "", $text );
}
function stringToUpper($text)
{
  if ($text != '')
  {
    $text = trim($text);
    return strtoupper(strtolower($text));
  }
  return $text;
}
function stringToTitle($text)
{
  if ($text != '')
  {
    $text = trim($text);
    return ucwords(strtolower($text));
  }
  return $text;
}

function getDropdownValue($dropdown, $id)
{
  global $arGlobalDropdowns;

  return $arGlobalDropdowns[$dropdown][$id];
}

function getDropdownValuesFromArIds($dropdown, $arIds, $returnType='string')
{
  global $arGlobalDropdowns;

  $arDropdown = $arGlobalDropdowns[$dropdown];
  $arValues = [];
  foreach ($arIds as $id)
  {
    $arValues[] = $arDropdown[$id];
  }

  if ($returnType == 'string')
  {
    return implode(', ', $arValues);
  }
  return $arValues;
}

function getErrorMsgNReturnLink($errorMsg)
{
  if ($errorMsg != '')
  {
    $previousPage = $_SERVER['HTTP_REFERER'];
    echo <<<EOQ
    <span class="alert alert-danger">{$errorMsg}</span><br><a href="{$previousPage}">Click here to go back</a>
EOQ;
    exit;
  }
}

function returnToPreviousPage()
{
  header('location: '.$_SERVER['HTTP_REFERER']);
}

function getCurrentDate($format="Y-m-d H:i:s")
{
  return date($format);
}

function getCurrentPage($pageTitle)
{
  $arCurrentPage = [
    'dashboard' => ''
    , 'dashboardevents' => ''
    , 'dashboardbookings' => ''
    , 'dashboardprofile' => ''
  ];
  $lblActive = 'active';
  $pageTitle = str_replace(' ', '', $pageTitle);
  $pageTitle = strtolower($pageTitle);
  switch($pageTitle)
  {
    case 'dashboard':
      $arCurrentPage['dashboard'] = $lblActive;
    break;
    case 'dashboardevents':
      $arCurrentPage['dashboardevents'] = $lblActive;
    break;
    case 'dashboardbookings':
      $arCurrentPage['dashboardbookings'] = $lblActive;
    break;
    case 'dashboardprofile':
      $arCurrentPage['dashboardprofile'] = $lblActive;
    break;
  }
  
  return $arCurrentPage;
}

function getCurrentFileName()
{
  $path = parse_url($_SERVER["SCRIPT_NAME"], PHP_URL_PATH);
  return pathinfo($path, PATHINFO_FILENAME);
}

function checkIfAdminPage()
{
  $currentPageName = getCurrentFileName();
  if (strpos($currentPageName, 'dashboard') !== false)
  {
    return true;
  }
  return false;
}

function blockOutToMainPage()
{
  header('Location: '.DEF_ROOT_PATH.'/login');
  exit;
}