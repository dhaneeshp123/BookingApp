<strong>Requirements:</strong> <br/>
<ul>
    <li>PHP : 7.4 and above</li>
    <li>MySQL: 10.4.18</li>  
</ul>

<strong>Setup</strong>
<br>
<ol>
    <li>Download the code</li>
    <li>Configure database properties in <pre>config/config.php</pre></li>
    <li>Execute the command <pre>composer install</pre></li>  
    <li>Execute the command <pre>php migrations.php up</pre></li>
    <li>
    Go to the public folder and execute the command<br/>
<pre>php -S localhost:8080</pre></li>
</ol>

The API documentation can be found at

https://documenter.getpostman.com/view/2217038/UV5WDJ62

<br/>
<strong>Testing</strong><br /><br />
Execute the command
<pre>php vendor/bin/phpunit --testdox test</pre>
