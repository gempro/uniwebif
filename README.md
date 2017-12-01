# uniwebif
Extern and Universal Webinterface for Linux Receiver's

If you're not happy with the Webinterface from your Linux Receiver, you should have a look at it.

Overview of the functions:

- EPG Crawler
- EPG Browser
- EPG to SQL
- Optimized for mobile devices

For hosting the script, i've used a Raspberry Pi Modell B with 512mB RAM. So less ressources are needed.

Requirements on Server/Webspace:

- Webserver like Apache2 with activated PHP
- Activated mysqli module for the SQL Database
- Activated allow_url_fopen for the usage from file_get_contents, to get the XML files from Receiver

Uniwebif was compatible with Enigma2 Receivers, but with some little changes it could be compatible with other too.

Watch Tutorial for more Information: https://www.youtube.com/watch?v=lj4EOlJzquk

Online Demo: http://uniwebif-demo.techweb.at

---

UPDATE INFO

When changing from 1.0 or 1.1 it's required to reinstall the whole SQL Database cause some tables was added.
