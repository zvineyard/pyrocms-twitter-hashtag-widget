<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Display Twitter hashtag search reults on your website.
 * 
 * @author 		Zac Vineyard
 * @website		http://zacvineyard.com
 * @package 	PyroCMS
 */

class Widget_Twitter_tag_feed extends Widgets
{

	/**
	 * The translations for the widget title
	 *
	 * @var array
	 */
	public $title = array(
		'en' => 'Twitter Tag Feed'
	);

	/**
	 * The translations for the widget description
	 *
	 * @var array
	 */
	public $description = array(
		'en' => 'Display Twitter hashtag search reults on your website.'
	);

	/**
	 * The author of the widget
	 *
	 * @var string
	 */
	public $author = 'Zac Vineyard';

	/**
	 * The author's website.
	 * 
	 * @var string 
	 */
	public $website = 'http://zacvineyard.com/';

	/**
	 * The version of the widget
	 *
	 * @var string
	 */
	public $version = '1.0';

	/**
	 * The fields for customizing the options of the widget.
	 *
	 * @var array 
	 */
	public $fields = array(
		array(
			'field' => 'tag',
			'label' => 'Tag',
			'rules' => 'required'
		),
		array(
			'field' => 'number',
			'label' => 'Number of tweets',
			'rules' => 'numeric'
		),
		array(
			'field' => 'show_images',
			'label' => 'Show Twitter user images?',
			'rules' => ''
		)
	);

	/**
	 * The URL used to get statuses from the Twitter API
	 *
	 * @var string
	 */
	private $feed_url = 'http://search.twitter.com/search.json?';

	/**
	 * The main function of the widget.
	 *
	 * @param array $options The options for the twitter tag and the number of tweets to display
	 * @return array 
	 */
	public function run($options)
	{
		if(isset($options['show_images']))
		{
			$options['show_images'] = 1;
		}
		else
		{
			$options['show_images'] = 0;
		}

		if (!$tweets = $this->pyrocache->get('twitter-'.$options['tag'].'-'.$options['number']))
		{
			$tweets = json_decode(@file_get_contents($this->feed_url.'&q=%23'.str_replace("#","",$options['tag']).'&rpp='.$options['number']));

			$this->pyrocache->write($tweets, 'twitter-'.$options['tag'].'-'.$options['number'], $this->settings->twitter_tag_cache);
		}

		$patterns = array(
			// Detect URL's
			'((https?|ftp|gopher|telnet|file|notes|ms-help):((//)|(\\\\))+[\w\d:#@%/;$()~_?\+-=\\\.&]*)' => '<a href="$0" target="_blank">$0</a>',
			// Detect Email
			'|([a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,6})|i' => '<a href="mailto:$1">$1</a>',
			// Detect Twitter @usernames
			'/(^|\s)@([a-z0-9_]+)/i' => '$1<a href="http://www.twitter.com/$2">@$2</a>',
			// Detect Twitter #tags
			'|#([a-z0-9-_]+)|i' => '<a href="https://twitter.com/search?q=%23$1" target="_blank">$0</a>'
		);

		if ($tweets)
		{
			foreach ($tweets->results as &$tweet)
			{
				$tweet->text = str_replace($tweet->from_user.': ', '', $tweet->text);
				$tweet->text = preg_replace(array_keys($patterns), $patterns, $tweet->text);
			}
		}

		// Store the feed items
		return array(
			'tag' => $options['tag'],
			'show_images' => $options['show_images'],
			'tweets' => $tweets ? $tweets : array(),
		);
	}

}