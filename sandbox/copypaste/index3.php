// 👍 ✔ originally posted on:
// https://dirask.com/posts/10Wwaj
// we can run this code online under above link ☘
// 

<!doctype html>
<html>
<body>
  <div>
    <img id="image" style="border: 1px solid silver; width: 320px; height: 240px" />
    <br /><br />
    <button onclick="pasteImage()">Paste image from clipboard</button>
  </div>
  <script>
   
    var ClipboardUtils = new function() {
        var permissions = {
            'image/bmp': true,
            'image/gif': true,
            'image/png': true,
            'image/jpeg': true,
            'image/tiff': true
        };
     
        function getType(types) {
            for (var j = 0; j < types.length; ++j) {
                var type = types[j];
                if (permissions[type]) {
                    return type;
                }
            }
            return null;
        }
        function getItem(items) {
            for (var i = 0; i < items.length; ++i) {
                var item = items[i];
                if(item) {
                    var type = getType(item.types);
                    if(type) {
                        return item.getType(type);
                    }
                }
            }
            return null;
        }
        function loadFile(file, callback) {
            if (window.FileReader) {
                var reader = new FileReader();
                reader.onload = function() {
                    callback(reader.result, null);
                };
                reader.onerror = function() {
                    callback(null, 'Incorrect file.');
                };
                reader.readAsDataURL(file);
            } else {
                callback(null, 'File api is not supported.');
            }
        }

        this.readImage = function(callback) {
            if (navigator.clipboard) {
                var promise = navigator.clipboard.read();
                promise
                    .then(function(items) {
                        var promise = getItem(items);
                        if (promise) {
                            promise
                              .then(function(result) {
                                  loadFile(result, callback);
                              })
                              .catch(function(error) {
                                  callback(null, 'Reading clipboard error.');
                              });
                        } else {
                            callback(null, null);
                        }
                    })
                    .catch(function(error) {
                        callback(null, 'Reading clipboard error.');
                    });
            } else {
                callback(null, 'Clipboard is not supported.');
            }
        };
    };

    // Usage example:

    var image = document.querySelector('#image');

    function pasteImage() {
        ClipboardUtils.readImage(function(data, error) {
            if (error) {
                console.log(error);
                return;
            }
            if (data) {
                image.src = data;
                return;
            }
            console.log('Image is not avaialble - copy image to clipboard.');
        });
    }
   
  </script>
</body>
</html>