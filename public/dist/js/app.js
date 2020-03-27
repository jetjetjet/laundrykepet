$.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

function showModal(title, content, options, callback){
$modal = cloneModal();
$modal.modal({
  show: false,
  backdrop: options.noClickOutside ? 'static' : true,
  keyboard: options.noClickOutside ? false : true
});
// Draws text.
var $modalTitle = $modal.find('.modal-title');
$modalTitle.html(title);
var $modalBody = $modal.find('.modal-body');
$modalBody.html(content);

$actionBtn = $modal.find('.modal-yes');
if (options.caption){
  $actionBtn.text(options.caption);
}

$actionBtn
  .removeClass('hidden')
  .addClass('btn-' + ((typeof options === 'object' ? options.btnType : options) || 'primary'));
if (callback){
  $modal.find('.modal-yes').click(function (){
    callback({ 
      confirmBtn: $(this), 
      modalTitle: $modalTitle, 
      modalBody: $modalBody,
      options: options,
      close: function (){
        $modal.modal('hide');
        $('#uiModalInstance').remove();
        $('.modal-backdrop').remove();
      }
    });
    if (!options || typeof options !== 'object' || !options.keepOpen){
      $modal.modal('hide');
    }
  });
}

$modal.modal('show');
if (options.noAutoFocus){
  setTimeout(function (){
    $modalBody.find('input[type=text]:visible:not([readonly]):not([disabled]),select:visible:not([disabled]),textarea:visible:not([readonly])').first().focus();
  }, 500);
}

return {
  modalTitle: $modalTitle, 
  modalBody: $modalBody,
  actionBtn: $actionBtn
};
}

function showPopupForm($btn, options, title, $popup, postUrl, getPostDataFn, successCallbackFn, failCallbackFn){
  var content = $('<form></form>').append($popup.html());
  options.noClickOutside = true;
  var modal = showModal(title, content, options, function (e)
    {
      $btn.prop('disabled', true);

      // Validates.
        var $form = e.modalBody.find('form');
      // if (!$form.valid()) return;

      var url = typeof postUrl === 'function' ? postUrl($form) : postUrl,
        postData = getPostDataFn($form),
        actualPostData = Object.assign({}, postData);
      delete actualPostData.tempData;

      $.post(url, actualPostData, function (data){
        $btn.prop('disabled', false);
        // Closes current modal.
        e.close();

        if (!data) return;
        if (!data.success){
          if (failCallbackFn){
            data.previousPostUrl = url;
            data.postData = postData;
            failCallbackFn(data);
          } else {
            console.log('e',data);
          }
        } else {
          successCallbackFn(data);
        }
      });
    });

  return modal;
}

window.wblgstc = {
    setupSuggestionsById: function ($search, $searchId, queryUrlFn, displayFn, onSelectedFn, $delay){
        if ($searchId){
            $search.data('selectedTypeahead', { id: $searchId.val() });
        }
        
        return $search
            .typeahead({
                minLength: 0,
                items: 10,
                delay: $delay === "undefined" ? 100 : $delay,
                showHintOnFocus: true,
                displayText: displayFn,
                highlighter: function (item){
                    /* prevent escape html */ 
                    return item;
                },
                source: function (query, process){
                    if (queryUrlFn(query, $search) == null) return;

                    return $.get(queryUrlFn(query, $search), function (data){
                        return process(data);
                    });
                },
                afterSelect: function (item){
                    $search.data('selectedTypeahead', item);
                    updateSelectedItem(item);
                },
                matcher: function (item){
                    return true;
                }
            })
            .typeaheadX()
            .keyup(function (e){
                if (e.keyCode === 27){
                    $search.data('selectedTypeahead', null);
                    updateSelectedItem(null);
                    $(this).val('');
                }
            })
            .blur(function (){
                if (!$(this).val().trim()){
                    $search.data('selectedTypeahead', null)
                    updateSelectedItem(null);
                } else if (!$search.data('selectedTypeahead')){
                    $(this).val('');
                }
            });

        function updateSelectedItem(selectedItem){
            $searchId.val(selectedItem ? selectedItem.id : null);
            if (onSelectedFn){
                onSelectedFn(selectedItem, $search);
            }
        }
    }
};

function cloneModal(){
  $('#uiModalInstance').remove();
  $('.modal-backdrop').remove();

  var $modal = $('#myModal').clone().appendTo('body');
  $modal.attr('id', 'uiModalInstance');

  return $modal;
}

$.fn.registerAddRow = function ($rowTemplateContainer, $addRow, rowAddedFn, validationFn){
      $(this).each(function (){
          var $targetContainer = $(this),
              $tbody = $targetContainer.find('> tbody'),
              currentRowIndex = ($tbody.length ? $tbody : $targetContainer).children().length - 1;
          $targetContainer.attr('wbl-last-row-index', currentRowIndex);
          ($tbody.length ? $tbody : $targetContainer).children().each(function (idx){
              $(this).attr('wbl-curr-row-index', idx);
          });

          var $addRowBtns = typeof $addRow === 'function' ? $addRow($targetContainer) : $addRow;
          $addRowBtns.on('click', function (){
              if (validateAddRow()){
                  addRow(true);
              }
          });
          $addRowBtns.on('addRow', function (){
              if (validateAddRow()){
                  addRow(false);
              }
          });

          function validateAddRow(){
              //validate
              if (validationFn && typeof validationFn === 'function'){
                  return validationFn();
              }

              return true; //no validation needed
          }

          function addRow(focus){
              // Clones.
              var $instance = cloneRow($targetContainer, $rowTemplateContainer);

              if (rowAddedFn && typeof rowAddedFn === 'function'){
                  rowAddedFn($instance);
              }
              
              // Custom setup.
              $targetContainer.triggerHandler("row-added", [$instance]);

              if (focus){
                  // Sets focus.
                  $instance.find('input[type=text]:visible:not([readonly]):not([disabled]),select:visible:not([disabled]),textarea:visible:not([readonly])').not('.no-autofocus').first().focus();
              }
          }
      });
}

function cloneRow($targetContainer, $rowTemplateContainer, rowIndex){
    var $rowTemplateTbody = $rowTemplateContainer.find('> tbody'),
        $instance = ($rowTemplateTbody.length ? $rowTemplateTbody : $rowTemplateContainer).children(":first").clone(),
        lastIndexName = 'wbl-last-row-index',
        currIndexName = 'wbl-curr-row-index';
    if (!rowIndex){
        rowIndex = Number($targetContainer.attr(lastIndexName) || 0) + 1;
        $targetContainer.attr(lastIndexName, rowIndex);
        $instance.attr(currIndexName, rowIndex);
    }

    // Sets index on name recursively all the way up.
    var grandParentIndices = $targetContainer.parents('[' + currIndexName + ']').map(function (){
            return $(this).attr(currIndexName);
        }).toArray(),
        parentIndices = grandParentIndices.concat(rowIndex);
    $instance.find('[name*="[]"]').each(function (){
        var $input = $(this);
        parentIndices.forEach(function (val){
            $input.attr('name', $input.attr('name').replace('[]', '[' + val + ']'));
        });
    });
    $instance.find('[wblgstc-link-params*="[]"]').each(function (){
        var $input = $(this);
        parentIndices.forEach(function (val){
            //$input.attr('wblgstc-link-params', $input.attr('wblgstc-link-params').replace('[]', '[' + val + ']'));
            $input.attr('wblgstc-link-params', $input.attr('wblgstc-link-params').split('[]').join('[' + val + ']'));
        });
    });

    // Adds.
    var $tbody = $targetContainer.find('> tbody');
    ($tbody.length ? $tbody : $targetContainer).append($instance);

    return $instance;
}

  $('table,.subitem-container')
      .on('click', '[remove-row]', function (e){
          var $tr = $(this).closest('tr,.panel,.rowpanel'),
              $table = $tr.closest('table,.subitem-container');
          $table.triggerHandler("row-removing", [$tr]);
          $tr.remove();
          $table.triggerHandler("row-removed", [$tr]);

          $table.attr('data-has-changed', '1');
      })
      .on('click', '[moveup-row]', function (e){
          var $row = $(this).closest("tr,.subitem,.subsubitem");
          var $previousRow = $row.prev();
          $row.insertBefore($previousRow);
          $row.attr('data-has-changed', '1');
          $row.prev().attr('data-has-changed', '1');

          $row.trigger('order-change', ['up', $previousRow]);
      })
      .on('click', '[movedown-row]', function (e){
          var $row = $(this).closest("tr,.subitem,.subsubitem");
          var $nextRow = $row.next();
          $row.insertAfter($nextRow);
          $row.attr('data-has-changed', '1');
          $row.next().attr('data-has-changed', '1');

          $row.trigger('order-change', ['down', $nextRow]);
      });