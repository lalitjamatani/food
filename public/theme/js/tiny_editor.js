function setEditor(tags) {
    tinymce.init({
        selector: 'textarea.tinymce-editor',
        // height: 300,
        menubar: false,
        plugins: [
            'advlist autolink lists link image charmap print preview anchor',
            'searchreplace visualblocks code fullscreen',
            'insertdatetime media table paste code help wordcount', 'image'
        ],
        toolbar: 'undo redo | formatselect | ' +
            'bold italic backcolor | alignleft aligncenter ' +
            'alignright alignjustify | bullist numlist outdent indent | ' +
            'removeformat | help | tags',
        content_css: '//www.tiny.cloud/css/codepen.min.css',
        setup: (editor) => {
            if (tags?.length) {
                var toHtml = (text) => {
                    return '{{' + text + '}}';
                };
                editor.ui.registry.addMenuButton('tags', {
                    text: 'Add tags',
                    fetch: (callback) => {
                    tags.map(x => {
                        x['onAction'] = () => editor.insertContent(toHtml(x.as)) // onAction needed for select event
                    });
                    let items = tags
                    callback(items);
                    }
                });
            }
        }
    });
}

function setEditorWithsubTag(tags)
{
    tags = JSON.parse(tags);
    var tagString = '';
    for (const [key, value] of Object.entries(tags)) {
        tagString+= key+' ';
        value.forEach(function(val){
            // console.log(val);
        });
        // console.log(value);

      }
      tagString+= 'service_list today_long staff_id missing_data SIGNOFF CS01DATA';
      console.log(tagString);
    tinymce.init({
        selector: 'textarea.tinymce-editor',
        height: 800,
        menubar: 'custom',
        plugins: [
            'advlist autolink lists link image charmap print preview anchor',
            'searchreplace visualblocks code fullscreen',
            'insertdatetime media table paste code help wordcount', 'image'
        ],
        toolbar: 'undo redo | formatselect | ' +
            'bold italic backcolor | alignleft aligncenter ' +
            'alignright alignjustify | bullist numlist outdent indent | ' +
            'removeformat | help | tags',

        menu: {
          custom: { title: 'Tags', items: tagString} //'required_information internal staff_task basicitem toggleitem'
        },
        setup: (editor) => {
          let toggleState = false;


        for(i = 0; i < tags.length; i++){

        }
          for (const [key, value] of Object.entries(tags)) {
            var arraySubItems = [];


            editor.ui.registry.addNestedMenuItem(key, {
                text: key,
                getSubmenuItems: function () {
                    var demo = value;
                    var newdemo = demo.map(function(v,i){
                      // console.log(v,i);
                      var column_name = v.relation == "secondary_contact" ? "secondary_"+v.column_name : v.column_name;
                      if(v.relation.includes("staff_task.")){
                        var columnArray = v.relation.split(".");
                        // console.log(columnArray);
                        column_name = 'staff_task_'+columnArray[1]+'_'+v.column_name;
                      }
                        return {
                            type: 'menuitem',
                            text: v.name,
                            onAction: () => editor.insertContent(`%`+column_name+`%`)
                            }
                    })
                    // console.log(newdemo);
                    return newdemo;
                  }
                // getSubmenuItems: () => arraySubItems
            });
            editor.ui.registry.addMenuItem('service_list', {
                text: 'Service List',
                onAction: () => editor.insertContent(`%service_list%`)
              });

              editor.ui.registry.addMenuItem('today_long', {
                text: 'TODAYLONG',
                onAction: () => editor.insertContent(`%today_long%`)
              });

              editor.ui.registry.addMenuItem('staff_id', {
                text: 'Staff Id',
                onAction: () => editor.insertContent(`%staff_id%`)
              });
              editor.ui.registry.addMenuItem('missing_data', {
                text: 'Missing Data',
                onAction: () => editor.insertContent(`%missing_data%`)
              });
              editor.ui.registry.addMenuItem('SIGNOFF', {
                text: 'SIGNOFF',
                onAction: () => editor.insertContent(`%SIGNOFF%`)
              });
              editor.ui.registry.addMenuItem('CS01DATA', {
                text: 'CS01DATA',
                onAction: () => editor.insertContent(`%CS01DATA%`)
              });
                // console.log(arraySubItems);
          }

          
      
        
        },
        content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:16px }'
      });
}

function setEditorWithOutTag() {
    tinymce.init({
        selector: 'textarea.tinymce-editor',
        // height: 300,
        menubar: false,
        plugins: [
            'advlist autolink lists link image charmap print preview anchor',
            'searchreplace visualblocks code fullscreen',
            'insertdatetime media table paste code help wordcount', 'image'
        ],
        toolbar: 'undo redo | formatselect | ' +
            'bold italic backcolor | alignleft aligncenter ' +
            'alignright alignjustify | bullist numlist outdent indent | ' +
            'removeformat | help | tags',
        content_css: '//www.tiny.cloud/css/codepen.min.css'
    });
}

function setEditorWithOutmenu() {
    tinymce.init({
        selector: 'textarea.tinymce-editor',
        // height: 300,
        menubar: false,
        readonly : 1,
        plugins: [
            'advlist autolink lists link image charmap print preview anchor',
            'searchreplace visualblocks code fullscreen',
            'insertdatetime media table paste code help wordcount', 'image'
        ],
        toolbar: false,
        content_css: '//www.tiny.cloud/css/codepen.min.css'
    });
}
