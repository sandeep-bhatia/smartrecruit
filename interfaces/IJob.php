<?php
/**
 * Created by JetBrains PhpStorm.
 * User: tarun
 * Date: 08/07/12
 * Time: 10:19 PM
 * To change this template use File | Settings | File Templates.
 */
interface IJob{

	public function getDescription();

	public function getExpirationDate();

	public function getIsActive();

	public function getJobId();

	public function getLocation();

	public function getPostedByLinkedInId();

	public function getPostingDate();

	public function getTitle();

}
