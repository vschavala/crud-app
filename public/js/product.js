$(document).ready(function () {
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
    },
  })

  /*  When user click add user button */
  $('#create-new-product').click(function () {
    $('#btn-save').val('create-product')
    $('#product_id').val('')
    $('#productForm').trigger('reset')
    $('#productCrudModal').html('Add New Product')
    $('#ajax-product-modal').modal('show')
    $('#modal-preview').attr('src', 'https://via.placeholder.com/150')
  })
  /* When click edit user */
  $('body').on('click', '.edit-product', function () {
    var product_id = $(this).data('id')
    $.get('product-list/' + product_id + '/edit', function (data) {
      $('#name-error').hide()
      $('#product_code-error').hide()
      $('#price-error').hide()
      $('#productCrudModal').html('Edit Product')
      $('#btn-save').val('edit-product')
      $('#ajax-product-modal').modal('show')
      $('#product_id').val(data.id)
      $('#name').val(data.name)
      $('#upc').val(data.upc)
      $('#price').val(data.price)
      $('#status').val(data.status)
      $('#modal-preview').attr('alt', 'No image available')
      if (data.image) {
        $('#modal-preview').attr(
          'src',
          SITEURL + '/public/product/' + data.image,
        )
        $('#hidden_image').attr(
          'src',
          SITEURL + '/public/product/' + data.image,
        )
      }
    })
  })
  $('body').on('click', '#delete-product', function () {
    var product_id = $(this).data('id')
    if (confirm('Are You sure want to delete !')) {
      $.ajax({
        type: 'get',
        url: SITEURL + '/product-list/delete/' + product_id,
        success: function (data) {
          var oTable = $('#laravel_datatable').dataTable()
          oTable.fnDraw(false)
        },
        error: function (data) {
          console.log('Error:', data)
        },
      })
    }
  })
})
$('body').on('submit', '#productForm', function (e) {
  e.preventDefault()
  var actionType = $('#btn-save').val()
  $('#btn-save').html('Sending..')
  var formData = new FormData(this)
  $.ajax({
    type: 'POST',
    url: SITEURL + '/product-list/store',
    data: formData,
    cache: false,
    contentType: false,
    processData: false,
    success: (data) => {
      $('#productForm').trigger('reset')
      $('#ajax-product-modal').modal('hide')
      $('#btn-save').html('Save Changes')
      var oTable = $('#laravel_datatable').dataTable()
      oTable.fnDraw(false)
    },
    error: function (data) {
      console.log('Error:', data)
      $('#btn-save').html('Save Changes')
    },
  })
})

function readURL(input, id) {
  id = id || '#modal-preview'
  if (input.files && input.files[0]) {
    var reader = new FileReader()
    reader.onload = function (e) {
      $(id).attr('src', e.target.result)
    }
    reader.readAsDataURL(input.files[0])
    $('#modal-preview').removeClass('hidden')
    $('#start').hide()
  }
}

$('#master').on('click', function (e) {
  if ($(this).is(':checked', true)) {
    $('.sub_chk').prop('checked', true)
  } else {
    $('.sub_chk').prop('checked', false)
  }
})

$('.delete_all').on('click', function (e) {
  var allVals = []
  $('.sub_chk:checked').each(function () {
    allVals.push($(this).attr('data-id'))
  })

  if (allVals.length <= 0) {
    alert('Please select row.')
  } else {
    var check = confirm('Are you sure you want to delete this row?')
    if (check == true) {
      var join_selected_values = allVals.join(',')

      $.ajax({
        url: $(this).data('url'),
        type: 'DELETE',
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        },
        data: 'ids=' + join_selected_values,
        // alert('ids='+join_selected_values)
        success: function (data) {
          if (data['success']) {
            $('.sub_chk:checked').each(function () {
              $(this).parents('tr').remove()
            })
            alert(data['success'])
          } else if (data['error']) {
            alert(data['error'])
          } else {
            alert('Whoops Something went wrong!!')
          }
        },
        error: function (data) {
          alert(data.responseText)
        },
      })

      $.each(allVals, function (index, value) {
        $('table tr')
          .filter("[data-row-id='" + value + "']")
          .remove()
      })
    }
  }
})

$('[data-toggle=confirmation]').confirmation({
  rootSelector: '[data-toggle=confirmation]',
  onConfirm: function (event, element) {
    element.trigger('confirm')
  },
})

$(document).on('confirm', function (e) {
  var ele = e.target
  e.preventDefault()

  $.ajax({
    url: ele.href,
    type: 'DELETE',
    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
    success: function (data) {
      if (data['success']) {
        $('#' + data['tr']).slideUp('slow')
        alert(data['success'])
      } else if (data['error']) {
        alert(data['error'])
      } else {
        alert('Whoops Something went wrong!!')
      }
    },
    error: function (data) {
      alert(data.responseText)
    },
  })

  return false
})
