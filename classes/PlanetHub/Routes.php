<?php
namespace PlanetHub;

//인터페이스도 클래스처럼 오토로더로 불러올 수 있습니다.
interface Routes{
  public function getRoutes(): array;
  public function getAuthentication(): \PlanetHub\Authentication; //지정한 타입을 반환시키기 위한 타입힌트로 부과적인 안전장치 생성.
}
