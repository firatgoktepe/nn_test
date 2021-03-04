<?php

/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Nax_Custom
 */
?>
<?php get_header(); ?>
<?php while (have_posts()) : the_post(); ?>
  <main id="site-main" class="page-<?php the_ID(); ?> <?php the_device_type(); ?>">
    <?php the_content(); ?>
  </main>
<?php endwhile; ?>
<?php get_footer(); ?>

sadsaddasdsa

<?php
/*



    button<br />
    <button type="button" onclick="alert('Hello World!')">Click Me!</button>
	<br />

    h1<br />
    <h1>Heading 1</h1>
    <p class="subtitle1">subtitle1</p>
    <br />

    h2<br />
    <h2>Heading 2</h2>
    <p class="subtitle2">subtitle2</p>
    <br />

    h3<br />
    <h3>Heading 3</h3>
    <p class="subtitle3">subtitle3</p>
    <br />

    h4<br />
    <h4>Heading 4</h4>
    <p class="subtitle4">subtitle4</p>
    <br />

    h1<br />
    <h5>Heading 5</h5>
    <p class="subtitle5">subtitle5</p>
    <br />

    h6<br />
    <h6>Heading 6</h6>
    <p class="subtitle6">subtitle6</p>
    <br />

    p<br />
    <p>This is a paragraph.</p>
    <p>This is another paragraph.</p>
    <br />

    input type="button"<br />
    <input type="button">
    <br />

    input type="checkbox"<br />
    <input type="checkbox">
    <br />

    input type="color"<br />
    <input type="color">
    <br />

    input type="date"<br />
    <input type="date">
    <br />

    input type="datetime-local"<br />
    <input type="datetime-local">
    <br />

    input type="email"<br />
    <input type="email">
    <br />

    input type="file"<br />
    <input type="file">
    <br />

    input type="hidden"<br />
    <input type="hidden">
    <br />

    input type="image"<br />
    <input type="image">
    <br />

    input type="month"<br />
    <input type="month">
    <br />

    input type="number"<br />
    <input type="number">
    <br />

    input type="password"<br />
    <input type="password">
    <br />

    input type="radio"<br />
    <input type="radio">
    <br />

    input type="range"<br />
    <input type="range">
    <br />

    input type="reset"<br />
    <input type="reset">
    <br />

    input type="search"<br />
    <input type="search">
    <br />

    input type="submit"<br />
    <input type="submit">
    <br />

    input type="tel"<br />
    <input type="tel">
    <br />

    input type="text"<br />
    <input type="text">
    <br />

    input type="time"<br />
    <input type="time">
    <br />

    input type="url"<br />
    <input type="url">
    <br />

    input type="week"<br />
    <input type="week">
    <br />

    select<br />
    <select name="cars">
      <option value="volvo">Volvo</option>
      <option value="saab">Saab</option>
      <option value="fiat">Fiat</option>
      <option value="audi">Audi</option>
    </select>
    <select name="cars" size="3" multiple>
      <option value="volvo">Volvo</option>
      <option value="saab">Saab</option>
      <option value="fiat">Fiat</option>
      <option value="audi">Audi</option>
    </select>
    <br />

    textarea<br />
    <textarea name="message" rows="5" cols="30">
    The cat was playing in the garden.
    </textarea>
    <br />

    pre<br />
    <pre>
      This is a some prefromatted text.

      With multiple rows.
    </pre>
    <br />

    strong<br />
    <p>HTML paragraph with <strong>strong</strong> Formatting</p>
    <br />

    em<br />
    <p>HTML paragraph with <em>emphasized</em> Formatting</p>
    <br />

    small<br />
    <p>HTML paragraph with <small>small</small> Formatting</p>
    <br />

    mark<br />
    <p>HTML paragraph with <mark>marked</mark> Formatting</p>
    <br />

    del<br />
    <p>HTML paragraph with <del>deleted</del> Formatting</p>
    <br />

    ins<br />
    <p>HTML paragraph with <ins>inserted</ins> Formatting</p>
    <br />

    sub<br />
    <p>HTML paragraph with <sub>subscripted</sub> Formatting</p>
    <br />

    sup<br />
    <p>HTML paragraph with <sup>superscripted</sup> Formatting</p>
    <br />

    q<br />
    <p>HTML paragraph with <q>short quotation</q> Formatting</p>
    <br />

    abbr<br />
    <p>HTML paragraph with <abbr>abbreviation</abbr> Formatting</p>
    <br />

    cite<br />
    <p>HTML paragraph with <cite>citation</cite> Formatting</p>
    <br />

    blockquote<br />
    <blockquote cite="http://www.worldwildlife.org/who/index.html">
    For 50 years, WWF has been protecting the future of nature.
    The world's leading conservation organization,
    WWF works in 100 countries and is supported by
    1.2 million members in the United States and
    close to 5 million globally.
    </blockquote>
    <br />

    address<br />
    <address>
    Written by John Doe.<br>
    Visit us at:<br>
    Example.com<br>
    Box 564, Disneyland<br>
    USA
    </address>
    <br />

    a<br />
    <a href="#">link text</a>
    <br />

    table<br />
    <table>
      <caption>table caption</caption>
      <tr>
        <th>header</th>
        <th>header</th>
      </tr>
      <tr>
        <td>data</td>
        <td>data</td>
      </tr>
    </table>
    <br />

    ul<br />
    <ul>
      <li>Coffee</li>
      <li>Tea</li>
      <li>Milk</li>
    </ul>
    <br />

    ol<br />
    <ol>
      <li>Coffee</li>
      <li>Tea</li>
      <li>Milk</li>
    </ol>
    <br />

    dl<br />
    <dl>
      <dt>Coffee</dt>
      <dd>- black hot drink</dd>
      <dt>Milk</dt>
      <dd>- white cold drink</dd>
    </dl>
    <br />

    div<br />
    <div>div element</div>
    <br />

    span<br />
    <span>span element</span>
    <br />

    code<br />
    <code>code element</code>
    <br />

    samp<br />
    <samp>samp element</samp>
    <br />

    kbd<br />
    <kbd>kbd element</kbd>
    <br />

    var<br />
    <var>var element</var>
    <br />

    fieldset<br />
    <fieldset>
      <legend>legend</legend>
      content
    </fieldset>
    <br />

    details<br />
    <details>
      <p> - by Refsnes Data. All Rights Reserved.</p>
      <p>All content and graphics on this web site are the property of the company Refsnes Data.</p>
    </details>
    <br />

    dialog<br />
    <dialog open>This is an open dialog window</dialog>
    <dialog>This is an closed dialog window</dialog>
    <br />

    figure<br />
    <figure>
      content
      <figcaption>figcaption</figcaption>
    </figure>
    <br />

    label<br />
    <label for="fuel">Fuel level:</label>
    <br />

    meter<br />
    <meter id="fuel"
           min="0" max="100"
           low="33" high="66" optimum="80"
           value="50">
        at 50/100
    </meter>
    <br />

    progress<br />
    <progress value="22" max="100"></progress>
    <br />

    summary<br />
    <summary>Copyright 1999-2014.</summary>
    <br />

    time<br />
    <time>10:00</time>

*/
