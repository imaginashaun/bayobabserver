<?php
/**
 * LaraClassified - Classified Ads Web Application
 * Copyright (c) BedigitCom. All Rights Reserved
 *
 * Website: https://bedigit.com
 *
 * LICENSE
 * -------
 * This software is furnished under a license and may be used and copied
 * only in accordance with the terms of such license and with the inclusion
 * of the above copyright notice. If you Purchased from CodeCanyon,
 * Please read the full License from here - http://codecanyon.net/licenses/standard
 */

namespace App\Observers;

use App\Helpers\Files\Storage\StorageDisk;
use App\Models\SliderImage;
use Illuminate\Support\Facades\Cache;

class SliderImageObserver
{
	/**
	 * Listen to the Entry deleting event.
	 *
	 * @param SliderImage $page
	 * @return void
	 */
	public function deleting($page)
	{
		// Storage Disk Init.
		$disk = StorageDisk::getDisk();
		
		// Delete the page picture
		if (!empty($page->picture)) {
			if ($disk->exists($page->picture)) {
				$disk->delete($page->picture);
			}
		}
	}
	
	/**
	 * Listen to the Entry saved event.
	 *
	 * @param SliderImage $page
	 * @return void
	 */
	public function saved(SliderImage $page)
	{
		// Removing Entries from the Cache
		$this->clearCache($page);
	}
	
	/**
	 * Listen to the Entry deleted event.
	 *
	 * @param SliderImage $page
	 * @return void
	 */
	public function deleted(SliderImage $page)
	{
		// Removing Entries from the Cache
		$this->clearCache($page);
	}
	
	/**
	 * Removing the Entity's Entries from the Cache
	 *
	 * @param $page
	 */
	private function clearCache($page)
	{
		Cache::flush();
	}
}
