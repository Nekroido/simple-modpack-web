# Simple modpack for Minecraft
Custom Minecraft modpack self-hosting made easy.

## Usage
Create a directory with a readable mod title and its jar file in the corresponding folders in the `mods` directory:
* `common`: for mods that are built for both server and client.
* `client`: for client-only mods (e.g. Optifine, Better Foliage, Damage Indicators etc.)
* `server`: for server-only mods (e.g. Bad Mobs, Friendly Fire, Clumps etc.)

The system will automatically build `manifest.json` and `modpack.zip` only from the latest jar files. Structure of the `mods` directory should look like this:

* `common`
  * `Bibliocraft`
    * `BiblioCraft[v2.4.5][MC1.12.2].jar`
  * `Industrial Craft 2 Experimental`
    * `industrialcraft-2-2.8.159-ex112.jar` <- note the extra file
    * `industrialcraft-2-2.8.73-ex112.jar`
* `client`
  * `JEI`
    * `jei_1.12.2-4.13.1.225.jar`
* `server`
  * `NetherPortalFix`
    * `NetherPortalFix_1.12.1-5.3.17.jar`

Run `docker-compose up` or create your own version of `docker-compose.yml`.

## Volumes
* /var/www/html/mods

## Ports
* 80

## URLs
### Manifest file
* `/manifest.json`: client version of a modpack manifest.
* `/manifest-server.json`: server version of a modpack manifest.
* `/manifest-full.json`: contains manifest details for all type mods present in the modpack.

### Zipped modpack
* `/modpack.zip`: caches and outputs client version of a modpack.
* `/modpack-server.zip`: caches and outputs server version of a modpack.

## Manifest file format
The system generates manifest JSON of a custom format as in the following example:
```json
{
   "type":"server",
   "updated":1544442659,
   "mods":{
      "common":[
         {
            "name":"Chisel",
            "filename":"Chisel-MC1.12.2-0.2.1.35.jar",
            "downloadUrl":"http://127.0.0.1/mods/common/Chisel/Chisel-MC1.12.2-0.2.1.35.jar",
            "size":4310011,
            "updated":1544442659,
            "checksum":"dbda5475859bcffc9f572c5a1506a1f4"
         }
      ],
      "server":[
         {
            "name":"Industrial Craft 2 Experimental",
            "filename":"industrialcraft-2-2.8.73-ex112.jar",
            "downloadUrl":"http://127.0.0.1/mods/server/Industrial%20Craft%202%20Experimental/industrialcraft-2-2.8.73-ex112.jar",
            "size":7982832,
            "updated":1544366678,
            "checksum":"ed584cbfb3dcc4e9fbe91acec623a93b"
         }
      ]
   }
}
```

## Contributing
Pull requests and suggestions are welcome!

## Authors
* [Nekroido](https://github.com/nekroido)

## Licence
[MIT](LICENSE)
