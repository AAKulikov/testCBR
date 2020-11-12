<?
function sql($arg) {
	$arg = trim($arg);
	$arg = strip_tags($arg);
	$arg = stripslashes($arg);
	return $arg;
}

function rus_date() {
	$translate = array("am" => "дп", "pm" => "пп", "AM" => "ДП", "PM" => "ПП", "Monday" => "Понедельник", "Mon" => "Пн", "Tuesday" => "Вторник","Tue" => "Вт", "Wednesday" => "Среда", "Wed" => "Ср", "Thursday" => "Четверг", "Thu" => "Чт", "Friday" => "Пятница", "Fri" => "Пт", "Saturday" => "Суббота", "Sat" => "Сб", "Sunday" => "Воскресенье", "Sun" => "Вс", "January" => "января", "Jan" => "Январь", "February" => "февраля", "Feb" => "Февраль", "March" => "марта", "Mar" => "Март", "April" => "апреля", "Apr" => "Апрель", "May" => "Май", "May" => "Мая", "June" => "июня", "Jun" => "Июнь", "July" => "июля", "Jul" => "Июль", "August" => "августа", "Aug" => "Август", "September" => "сентября", "Sep" => "Сентябрь", "October" => "октября", "Oct" => "Октябрь", "November" => "ноября", "Nov" => "Ноябрь", "December" => "декабря", "Dec" => "Декабрь", "st" => "ое", "nd" => "ое", "rd" => "е", "th" => "ое");
    if (func_num_args() > 1) {
       $timestamp = func_get_arg(1);
       return strtr(date(func_get_arg(0), $timestamp), $translate);
    } else {
        return strtr(date(func_get_arg(0)), $translate);
    }
}
