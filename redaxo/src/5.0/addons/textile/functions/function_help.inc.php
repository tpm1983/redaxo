<?php
/**
 * Textile Addon
 *
 * @author markus[dot]staab[at]redaxo[dot]de Markus Staab
 * 
 * @package redaxo4
 * @version svn:$Id$
 */

function rex_a79_help_overview()
{
  global $REX;

  // check perms
  if(!$REX['USER']->hasPerm('textile[help]'))
  {
  	return;
  }

  $formats = rex_a79_help_overview_formats();

  echo '<div class="a79_help_overview">
          <h3 class="a79">'. $REX['I18N']->msg('textile_instructions') .'</h3>
          <table style="width: 100%">
            <colgroup>
              <col width="50%" />
              <col width="50%" />
            </colgroup>
        ';
  foreach($formats as $format)
  {
    $label = $format[0];
    $id = preg_replace('/[^a-zA-z0-9]/', '', htmlentities($label));

    echo '
            <thead>
              <tr>
                <th colspan="3"><a href="#" onclick="toggleElement(\''. $id .'\'); return false;">'. htmlspecialchars($label) .'</a></th>
              </tr>
            </thead>

            <tbody id="'. $id .'" style="display: none">
              <tr>
                <th>'. $REX['I18N']->msg('textile_input') .'</th>
                <th>'. $REX['I18N']->msg('textile_preview') .'</th>
              </tr>
           ';

    foreach($format[1] as $perm => $formats)
    {
      foreach($formats as $_format)
      {
        $desc = $_format[0];

        $code = '';
        if(isset($_format[1]))
        	$code = $_format[1];

        if($code == '')
          $code = $desc;

        $code = trim(rex_a79_textile($code));

        echo '<tr>
                <td>'. nl2br(htmlspecialchars($desc)) .'</td>
                <td>'. $code .'</td>
              </tr>
              ';
      }
    }

    echo '</tbody>';
  }
  echo '</table>';
  echo '</div>';
}

function rex_a79_help_overview_formats()
{
  return array(
    rex_a79_help_headlines(),
    rex_a79_help_formats(),
    rex_a79_help_links(),
    rex_a79_help_footnotes(),
    rex_a79_help_lists(),
    rex_a79_help_tables(),
  );
}

function rex_a79_help_headlines()
{
  global $REX;

  return array($REX['I18N']->msg('textile_headlines'),
    array(
    'headlines1-3' =>
      array(
        array('h1. '. $REX['I18N']->msg('textile_headline') .' 1'),
        array('h2. '. $REX['I18N']->msg('textile_headline') .' 2'),
        array('h3. '. $REX['I18N']->msg('textile_headline') .' 3'),
      ),
    'headlines4-6' =>
      array(
        array('h4. '. $REX['I18N']->msg('textile_headline') .' 4'),
        array('h5. '. $REX['I18N']->msg('textile_headline') .' 5'),
        array('h6. '. $REX['I18N']->msg('textile_headline') .' 6'),
      ),
    )
  );
}

function rex_a79_help_formats()
{
  global $REX;

  return array($REX['I18N']->msg('textile_text_formatting'),
    array(
    'text_xhtml' =>
      array(
        array('_'. $REX['I18N']->msg('textile_text_italic') .'_'),
        array('*'. $REX['I18N']->msg('textile_text_bold') .'*'),
      ),
    'text_html' =>
      array(
        array('__'. $REX['I18N']->msg('textile_text_italic') .'__'),
        array('**'. $REX['I18N']->msg('textile_text_bold') .'**'),
      ),
    'cite' =>
      array(
        array('bq. '. $REX['I18N']->msg('textile_text_cite')),
        array('??'. $REX['I18N']->msg('textile_text_source_author') .'??'),
      ),
    'overwork' =>
      array(
        array('-'. $REX['I18N']->msg('textile_text_strike') .'-'),
        array('+'. $REX['I18N']->msg('textile_text_insert') .'+'),
        array('^'. $REX['I18N']->msg('textile_text_sup') .'^'),
        array('~'. $REX['I18N']->msg('textile_text_sub') .'~'),
      ),
    'code' =>
      array(
        array('@<?php echo "Hi"; ?>@'),
      ),
    )
  );
}

function rex_a79_help_links()
{
  global $REX;

  return array($REX['I18N']->msg('textile_links'),
    array(
    'links_intern' =>
      array(
        array ($REX['I18N']->msg('textile_link_internal') .':redaxo://5'),
        array ($REX['I18N']->msg('textile_link_internal_anchor') .':redaxo://7#AGB'),
      ),
    'links_extern' =>
      array(
        array ($REX['I18N']->msg('textile_link_external') .':http://doku.redaxo.de'),
        array ($REX['I18N']->msg('textile_link_external_anchor') .':http://www..redaxo.de#news'),
      ),
    'links_attributes' =>
      array(
        array ($REX['I18N']->msg('textile_link_attr_title') .':files/test.jpg'),
        array ($REX['I18N']->msg('textile_link_attr_rel') .':files/test.jpg'),
        array ($REX['I18N']->msg('textile_link_attr_title_rel') .':files/test.jpg'),
      ),
    'anchor' =>
      array(
        array ($REX['I18N']->msg('textile_link_anchor') .":\n\np(#Impressum). ". $REX['I18N']->msg('textile_link_anchor_text')),
      ),
    )
  );
}

function rex_a79_help_footnotes()
{
  global $REX;

  return array($REX['I18N']->msg('textile_footnotes'),
    array(
    'footnotes' =>
      array(
        array($REX['I18N']->msg('textile_footnote_text'). '[1] ..'),
        array('fn1. '. $REX['I18N']->msg('textile_footnote_note')),
      ),
    )
  );
}

function rex_a79_help_lists()
{
  global $REX;

  return array($REX['I18N']->msg('textile_lists'),
    array(
    'lists' =>
      array(
        array($REX['I18N']->msg('textile_numeric_list') .":\n# redaxo.de\n# forum.redaxo.de"),
        array($REX['I18N']->msg('textile_enum_list') .":\n* redaxo.de\n* forum.redaxo.de"),
      )
    )
  );
}

function rex_a79_help_tables()
{
  global $REX;

  return array($REX['I18N']->msg('textile_tables'),
    array(
    'tables' =>
      array(
        array("|_. Id|_. Name|\n|1|Peter|"),
        array("|www.redaxo.de|35|\n|doku.redaxo.de|32|\n|wiki.redaxo.de|12|"),
      )
  ));
}