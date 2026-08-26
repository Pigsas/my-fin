
# xDebug

```bash
./scripts/backend.sh /enable_xdebug.sh 
```
Click on Configure servers (or via File->Settings->PHP->Debug)

* Check `Break at first line in PHP scripts`

Click on Configure servers (or via File->Settings->PHP->Servers)

* Add new server configuration with `+` sign. Enter:
  * Name: `php.symfony` (lower/upper case matters)
  * Host: `127.0.0.1`
  * Port: `8000`
  * Debugger: `Xdebug`
  * _`Checked`_ Use path mappings:
      * Near path of your project in _File/Directory_ column
      * Add `/code` in _Absolute path on the server_ column.
      * Value is **saved only** when you press **Enter**
        (or clicked somewhere else)
  * Save settings by clicking `OK`



