<?php

/**
 * Quotes : inherits single Quote
 *
 */

class Quotes extends Quote
{
    protected $default_fields = ['body', 'quote_id', 'fullname', 'slug', 'photo', 'author_slug', 'source', 'total'];
    public $quotes;
    private $db;
    public $f3;
    function __construct()
    {
        global $f3, $db;
        $this->f3 = $f3;
        $this->db = $db;
        $this->quotes = new DB\SQL\Mapper($db, 'all_quotes_full', null, $this->f3->DB_CACHE_EXPIRATION);
        $f3->set('CORS.origin', '*');
    }

    function search($f3)
    {
        if (!$this->f3->exists('GET.query') || empty($this->f3->get('GET.query'))) {
            $this->f3->error(400, 'No criteria specified: try using ?query=value');
        }
        $query = $f3->get('GET.query');


        $result = $this->db->exec(
            "SELECT * FROM searchable WHERE value like LOWER(:query)",
            array(':query' => "%$query%")
        );
        $result = array("suggestions" => $result);
        send_json($result);
    }

    function fixSlugs($f3)
    {
        $this->quotes = new DB\SQL\Mapper($this->db, 'quotes');
        $missing_slugs = $this->quotes->find('slug is NULL');
        if (empty($missing_slugs)) {
            die('All good: no empty slug.');
        }
        pr($missing_slugs);
        exit;
        // Fix missing slugs by creating one and then saving it back in the db.
        $quote = new DB\SQL\Mapper($this->db, 'quotes');
        foreach ($missing_slugs  as $q) {
            $quote->load(
                array('id=?', $q->id)
            );
            $quote->slug = create_slug($q->quote);
            $quote->save();
            echo "<br>slug created: " . $quote->slug;
        }
        echo '<p>Done!';
    }
}
