<?php
namespace PlanetHub;

//글 서식도구를 템플릿 파일안에서 다루기 위한 Class...
class Markdown{
  private $string;
  
  public function __construct($markDown){
    $this->string = $markDown;
  }
  
  public function toHtml(){
    // $this->string를 HTML로 변환
		$text = htmlspecialchars($this->string, ENT_QUOTES, 'UTF-8');

		// strong (굵게)
		$text = preg_replace('/__(.+?)__/s', '<strong>$1</strong>', $text);
		$text = preg_replace('/\*\*(.+?)\*\*/s', '<strong>$1</strong>', $text);

		// emphasis (기울임)
		$text = preg_replace('/_([^_]+)_/', '<em>$1</em>', $text);
		$text = preg_replace('/\*([^\*]+)\*/', '<em>$1</em>', $text);

		// 윈도우 개행 문자(\r\n)를 유닉스 형식(\n)으로 변환
		$text = str_replace("\r\n", "\n", $text);
		// 매킨토시 개행 문자(\r)를 유닉스 형식(\n)으로 변환
		$text = str_replace("\r", "\n", $text);

		// 문단 나누기
		$text = '<p>' . str_replace("\n\n", '</p><p>', $text) . '</p>';
		// 줄바꿈
		$text = str_replace("\n", '<br>', $text);

		// [링크 텍스트](링크 URL)
		$text = preg_replace(
		'/\[([^\]]+)]\(([-a-z0-9._~:\/?#@!$&\'()*+,;=%]+)\)/i',
		'<a href="$2">$1</a>', $text);
        
    return $text;
  }
} 