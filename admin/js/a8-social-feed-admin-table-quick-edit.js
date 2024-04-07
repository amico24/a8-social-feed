jQuery(document).ready(function($){
    //only on categories for now
    //need to make sure only one edit menu exists at one time
    //console.log(table_data);
    let row;
    $(".asf-quick-edit").on('click', function(){
        console.log("edit button clicked");
        row = $(this).parents("tr");
        row.hide();
        let html = "";

        html += '<tr class="inline-edit-row">';
        html += '<td colspan = "3">';
        html += '<div class="inline-edit-wrapper">';
        html += '<fieldset class="inline-edit-col-left">';
        html += '<legend class="inline-edit-legend">Quick Edit</legend>';
        html += '<form method="POST">';
        html += '<input type="text" id="edit_cat_name" name="edit_cat_name" placeholder="Edit Category Name">';
        html += '<div class="submit inline-edit-save">';
        html += '<button type="button" class="button button-primary save asf-quick-edit-save">Update</button>';
        html += '<button type="button" class="button cancel asf-quick-edit-cancel">Cancel</button>';
        html += '</div>';
        html += '</form>';
        html += '</fieldset>';
        html += '</div>';
        html += '</td>';
        html += '</tr>';
        row.after(html);
    });

    $("body").on('click',".asf-quick-edit-cancel",function(){
        let edit_menu = $(this).parents("tr");
        edit_menu.hide();
        row.show();
    });

    $("body").on('click',".asf-quick-edit-save",function(){
        let edit_menu = $(this).parents("tr");
        /*
        edit_menu.hide();
        row.show();*/
        //get input
        let new_name = $('#edit_cat_name').val();
        let old_name = row.children('.column-name').contents().filter(function(){
            return this.nodeType == 3; //selects only the text in the html element
        }).text().trim();

        console.log(old_name);
        
        $.ajax({
            url:table_data.ajax_url,
            data:{
                action: 'edit_category',
                category_data:{
                    new_name: new_name,
                    old_name: old_name
                }
            },
            dataType:'json',
            type:'POST'
        }).done(function(response){
            console.log("Post request sent successfully");
            console.log(response);
        
            edit_menu.hide();
            row.show();
            row.children('.column-name').contents().filter(function(){
                return this.nodeType == 3;
            }).each(function(){
                this.textContent = this.textContent.replace(old_name, new_name);
            });//change name in table already
        });

    });
});