$.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$('body')
.on('click', '[delete-action]', function (e){
  e.preventDefault();

  var href = $(this).attr('delete-action');
  var title = $(this).attr('delete-title');
  var message = $(this).attr('delete-message');
  var successUrl = $(this).attr('delete-success-url');
  var $table = $(this).closest('table');

  // Enables modal on current element.
  $(this).attr('data-toggle', 'modal');
  $(this).attr('data-target', '#uiModalInstance');

  var $modal = cloneModal();
  $modal
    .modal({
        show: false,
    })
    .on('show.bs.modal', function (){
      // Draws text.
      $modal.find('.modal-title').html(title);
      $modal.find('.modal-body').html('<span class="fa fa-question-circle fa-lg"></span>&nbsp;' + message);

      // Shows and attaches click event.
      $modal.find('.modal-action-cancel').removeClass('d-none')
      $modal.find('.modal-action-delete').removeClass('d-none')
      .click(function (){
          $modal.modal('hide');
          $.post(href, function (data){
            console.log(successUrl)
            if (!data) return;

            if (data.success){
              toastr.success(data.successMessages)
              setTimeout(() => {
                window.location = successUrl;
              }, 1000)
            } else {
              showErrorMessages(data);
            }
          });
      });
    });
})

function showErrorMessages(data){
  if (!data) return;
  if ((!data.errors) && (!data.errorMessages || !data.errorMessages.length)) return;

  var $modal = cloneModal();
  $modal.modal({
      show: false,
  });

  var html = '';
  if (data.errors){
    var errorMessages = [];
    for (var field in data.errors){
        if (!data.errors.hasOwnProperty(field)) continue;
        if (!data.errors[field] || !data.errors[field].length) continue;
        errorMessages.push.apply(errorMessages, data.errors[field]);
    }

  html += getMessages('danger', errorMessages);
  } else {
    var html = getSimpleMessages('danger', data.errorMessages);
    if (data.debugMessages && data.debugMessages.length){
        html += getSimpleMessages('danger', data.debugMessages);
    }
  }

  if (data.refData){
    var refDataHtml = data.refData 
      ? ('<ul>' + data.refData.split('#@#').map(function (val){
        return '<li>' + val + '</li>';
      }).join('') + '</ul>')
      : '';
    html += refDataHtml;
  }

  // Draws text.
  $modal.find('.modal-title').html('Error');
  $modal.find('.modal-body').html(html);

  // Shows and attaches click event.
  $modal.find('.modal-action-ok').removeClass('d-none');

  // Shows modal.
  $modal.modal('show');
}

function showMessages(type, title, messages, callback){
  if (!messages) return;
          
  var $modal = cloneModal();
  $modal.modal({
      show: false,
  });
  // Draws text.
  $modal.find('.modal-title').html(title);
  $modal.find('.modal-body').html(getSimpleMessages(type, [messages]));

  // Shows and attaches click event.
  $modal.find('.modal-action-ok').removeClass('d-none');

  if (callback){
    callback();
    $modal.on('d-none.bs.modal', function (){
    });
  }

  // Shows modal.
  $modal.modal('show');
}

function getSimpleMessages(type, messages){
  if (!messages || !messages.length) return null;

  var html = '';
  html += type ? '<ul class="list-unstyled">' : '<ul>';
  messages.forEach(function (val){
    var textMessage = (val.message || val);
    if (textMessage === 'DB_VALIDATION_FAILED')
      textMessage = $('[locale=wblgstc]').attr('messages-errorDatabaseValidationFailed');

    switch (val.type || type){
      case 'success':
        html += '<li class="text-success"><span class="fa fa-check fa-fw"></span>&nbsp;' + textMessage + '</li>';
        break;
      case 'danger':
        html += '<li class="text-danger"><span class="fa fa-close fa-fw"></span>&nbsp;' + textMessage + '</li>';
        break;
      case 'debug':
        html += '<li class="text-danger"><span class="fa fa-gear fa-fw"></span>&nbsp;' + textMessage + '</li>';
        break;
      default:
        html += '<li>' + textMessage + '</li>';
    }

  });
  html += '</ul>';
  return html;
}
      
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
  $modal.find('.modal-action-cancel').removeClass('d-none');
  $actionBtn = $modal.find('.modal-action-yes');
  if (options.caption){
    $actionBtn.text(options.caption);
  }

  $actionBtn
    .removeClass('d-none')
    .addClass('btn-' + ((typeof options === 'object' ? options.btnType : options) || 'primary'));
  if (callback){
    $modal.find('.modal-action-yes').click(function (){
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
    //$btn.prop('disabled', true);

    // Validates.
      var $form = e.modalBody.find('form');
    if (!$form.valid()) return;

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

function submitPopup($btn, btnType, content, postUrl, failCallbackFn, options, modalOptions){
  var confirmOptions = { btnType: btnType, keepOpen: true, noClickOutside: true },
    mergedModalOptions = Object.assign({}, confirmOptions, modalOptions);
    showModalPopup($('[locale=wblgstc]').attr('fields-confirm'), content, mergedModalOptions, function (e){
    $btn.prop('disabled', true);

    // Shows waiting info.
    e.confirmBtn.prop('disabled', true);
    e.modalTitle.html($('[locale=wblgstc]').attr('messages-pleaseWait'))
    e.modalBody.html('<div class="progress"><div class="progress-bar progress-bar-striped active" style="width: 100%"></div></div>');

    $.post(
        postUrl,
        options && options.postData ? (typeof options.postData === 'function' ? options.postData($btn) : options.postData) : null,
        function (data){
          $btn.prop('disabled', false);

          // Closes current modal.
          e.close();

          if (!data) return;
          if (!data.success){
            if (failCallbackFn){
              failCallbackFn(data);
            } else {
              showErrorMessages(data);
            }
          } else {
            var messages = [];
            if (data.successMessages && data.successMessages.length){
              toastr.success(data.successMessages)
            }

            if (data.errorMessages && data.errorMessages.length){
              messages = messages.concat(data.errorMessages.map(function (val){
                return { type: 'danger', message: val };
              }));
            }
            setTimeout(() => {
              location.reload();
            }, 1500);
          }
      }
    );
  });
}

function showModalPopup (title, content, options, callback, callback2, callbackCancel){
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

  // Shows and attaches click event.
  $modal.find('.modal-action-cancel').removeClass('d-none');
  $actionBtn = $modal.find('.modal-action-yes');
  if (options.caption){
       $actionBtn.text(options.caption);
  }

  if (options.modalSize){
       $modal.find('.modal-dialog').addClass(options.modalSize);
  }

  $actionBtn
      .removeClass('d-none')
      .addClass('btn-' + ((typeof options === 'object' ? options.btnType : options) || 'primary'));

  if (callback){
      $modal.find('.modal-action-yes').click(function (){
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

  if (callback2){
      $actionBtn2 = $modal.find('.modal-action-yes-option2');
      if (options.caption2){
          $actionBtn2.text(options.caption2);
      }

      $actionBtn2
          .removeClass('d-none')
          .addClass('btn-' + ((typeof options === 'object' ? options.btnType2 : options) || 'primary'));
      $actionBtn2.click(function (){
          callback2({
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

  $modal.on('.d-none.bs.modal', function (){
      if (callbackCancel && typeof callbackCancel === 'function'){
          callbackCancel();
      }
  });

  // Shows modal.
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

// window.wblgstc = {
//   setupSuggestionsById: function ($search, $searchId, queryUrlFn, displayFn, onSelectedFn, $delay){
//     if ($searchId){
//         $search.data('selectedTypeahead', { id: $searchId.val() });
//     }
    
//     return $search
//     .typeahead({
//       minLength: 0,
//       items: 10,
//       delay: $delay === "undefined" ? 100 : $delay,
//       showHintOnFocus: true,
//       displayText: displayFn,
//       highlighter: function (item){
//         /* prevent escape html */ 
//         return item;
//       },
//       source: function (query, process){
//         if (queryUrlFn(query, $search) == null) return;

//         return $.get(queryUrlFn(query, $search), function (data){
//           return process(data);
//         });
//       },
//       afterSelect: function (item){
//         $search.data('selectedTypeahead', item);
//         updateSelectedItem(item);
//       },
//       matcher: function (item){
//         return true;
//       }
//     })
//     .typeaheadX()
//     .keyup(function (e){
//         if (e.keyCode === 27){
//           $search.data('selectedTypeahead', null);
//           updateSelectedItem(null);
//           $(this).val('');
//         }
//     })
//     .blur(function (){
//       if (!$(this).val().trim()){
//         $search.data('selectedTypeahead', null)
//         updateSelectedItem(null);
//       } else if (!$search.data('selectedTypeahead')){
//         $(this).val('');
//       }
//     });

//     function updateSelectedItem(selectedItem){
//       $searchId.val(selectedItem ? selectedItem.id : null);
//       if (onSelectedFn){
//         onSelectedFn(selectedItem, $search);
//       }
//     }
//   }
// };

function cloneModal(){
  $('#uiModalInstance').remove();
  $('.modal-backdrop').remove();

  var $modal = $('#myModal').clone().appendTo('body');
  $modal.attr('id', 'uiModalInstance');

  return $modal;
}

$.fn.setupPopupForm = function (btnType, content, postUrl, failCallbackFn, options, beforeFunction){
  $(this).click(function (){
    if ($.isFunction(beforeFunction)) {
      //return false stop executing
      if(!beforeFunction())
        return false;
    }

    if ($('form').find('[data-has-changed=1]:first').length){
      toastr.error('Simpan perubahan terlebih dahulu.')
      return;
    }

    if (options && options.validateFormFirst && !$('form').valid()) return;

    submitPopup($(this), btnType, content, postUrl, failCallbackFn, options);
  });

  return $(this);
};

$.fn.setupMask = function (precision){
  $(this).each(function (){
    var $input = $(this);

    var html = '<input type="text" class="form-control input-sm text-right masking" />'; 
    var $mask = $(html).insertAfter($input);
    
    $input
      .blur(function (){
        if ($input.data('programmaticallyfocus')) return;

        $(this).toggleClass('d-none');
        $mask.toggleClass('d-none');
      })
      .change(function (){
        inputChange($(this));
      })
      .on('requestUpdateMask', function (){
      inputChange($(this));
      })
      .on('disabledMask', function (event, bool){
        $mask.prop('disabled', bool);
      })
      .on('readOnlyMask', function (event, bool){
        $mask.prop('readOnly', bool);
      });;
      
      $mask
      .focus(function (){
        if (!$input.prop('readonly')){
          $(this).toggleClass('d-none');
          $input.toggleClass('d-none');

          // Firefox @!&*^@!#^
          $input.data('programmaticallyfocus', true);
          $input.focus();
          $input.select();
          $input.removeData('programmaticallyfocus');
        }
      });

    $mask.attr('required', $input.prop('required'));
    $mask.prop('disabled', $input.prop('disabled'));
    $mask.prop('required', $input.prop('required'));
    if ($input.prop('autofocus')){
      setTimeout(function (){
        $mask.focus();
      });
    }

    // Initial state to show value.
    inputChange($input);
    $input.addClass('d-none');

    function inputChange($self){
      var valueText = $self.val(),
        value = valueText ? Number(valueText) : null;
      value = isNaN(value) ? null : value;
      $mask.val(value === null ? null : value.toLocaleString(undefined, { minimumFractionDigits: precision === undefined ? (value % 1 === 0 ? 0 : 2) : precision }));

      ['readonly', 'disabled', 'required', 'min', 'max', 'placeholder'].forEach(function (val){
        copyAttr(val);
      });

      function copyAttr(attr){
        if (!!$self.attr(attr)) {
          $mask.prop(attr, $self.prop(attr));
        } else {
          $mask.removeAttr(attr);
        }
      }
    }
  });
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

  function inputSearch(inputId, urlSearch, width, callBack)
  {
    let input = $(inputId);
    input.select2({
      placeholder: 'Cari...',
      width: width,
      ajax: {
        url: urlSearch,
        dataType: 'json',
        delay: 250,
        processResults: function (data) {
          return {
            results:  $.map(data, function (item) {
              return callBack(item)
            })
          };
        },
        cache: false
      }
    })
  }