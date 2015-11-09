<?php 
/** 
 * default.php default layout file
 * 
 * $content content to display
 */
?>
<!DOCTYPE html>
<html>
  <head>
    <title><?php echo $this->title?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="<?php echo HTMLROOT?>css/bootstrap.min.css" rel="stylesheet" media="screen">
    <style>
      body {
        padding-top: 60px;
      }
      .ASC:after {
        content:' \25B2';
      }
      .DESC:after {
        content:' \25BC';
      }
      th {
        min-width: 90px;
      }
    </style>
    <link href="<?php echo HTMLROOT?>css/bootstrap-responsive.css" rel="stylesheet" media="screen">
    <script src="http://code.jquery.com/jquery.js"></script>
    <script src="<?php echo HTMLROOT?>js/bootstrap.min.js"></script>
    <script>

      /**
       * Makes the AJAX call to refresh the sortable table, passing the values in hidden fields
       * to indicate sort attribute and direction, pagination, and filters
       */
      function refreshTable() {
    	  $.get($('#ajaxtable').attr('ajaxurl'), {
  			ajax : true,
            sortAttribute : $('#sortAttribute').val(),
  		    sortDirection: $('#sortDirection').val(),
  		    paginationPerPage: $('#paginationPerPage').val(),
  		    paginationPageNumber: $('#paginationPageNumber').val(),
  		    filter: $('#filter').val()
  	      }, function(data){
  		    $('#ajaxtable').replaceWith(data);
  		  });
      }

      /**
       * jQuery on ready
       */
      $(function() {


    	/**
         * Create event handler when sortable headers are clicked
         *
         * When a sortable header is clicked, the class indicating sort direction is updated,
         * the hidden sorting fields changed, and the table refreshed
         */  
		$(document).on("click", ".sortable", function() {
		  attribute = $(this).text();
		  var direction;
		  if ($(this).hasClass('ASC')) {
		    direction = 'DESC';
		  } else if ($(this).hasClass('DESC')) {
			direction = 'NONE';
		  } else {
			direction = 'ASC';
		  }
		  $('#sortAttribute').val(attribute);
		  $('#sortDirection').val(direction);
		  refreshTable();
		});

		/**
	     * Creates the event handler for clicking on the pages per view selector
	     *
	     * When the pages per view selector is clicked, the selected number is passed to the hidden
	     * pages per view field, the current page is reset to 0 and the table is refreshed
	     */
        $(document).on("click", ".paginationLimit a", function() {
          $('#paginationPerPage').val($(this).text());
          $('#paginationPageNumber').val('0');
          refreshTable();
        });

        /**
         * Creates the event handler for clicking on the categories filter
         *
         * When the categories filter selector is clicked, the hidden filter field is updated with
         * the selected filter, the current page field is reset to 0 and the table is refreshed
         */
        $(document).on("click", ".filter a", function() {
            category = $(this).text();
            $('#filter').val(category);
            $('#paginationPageNumber').val('0');
            refreshTable();
          });

        /**
         * Event handler for clicking on the pagination navigator
         *
         * When a button in the pagination navigator is clicked, the hidden pagination field for
         * the current page is updated and the table is refreshed 
         */
        $(document).on("click", ".navigator button", function() {
            $('#paginationPageNumber').val($(this).attr('page') - 1);
            refreshTable();
          });

        /**
         * Event handler for clicking on the delete button in tables
         *
         * This event handler will cause the "deleteBox" dialog to show and ask for a confirmation
         * prior to redirecting to the delete page.  Any data required is passed to the dialog for
         * further processing.
         */
        $(document).on("click", ".delete", function(e) {
          e.preventDefault();
          $('#deleteTarget').text($(this).parent().siblings('.itemName').text());
          $('.btnConfirmDelete').attr('href', $(this).attr('href'));
          $('#deleteBox').modal('show');
        });            
		
      });
    </script>
  </head>
  <body>
    <div class="container">
      <?php if ($alert = testProject::getAlert()):?>
      <div class="alert alert-<?php echo $alert['class']?>">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <?php echo $alert['message'];?>
      </div>
      <?php endif;?>
      <?php echo $content?>
    </div>
  </body>
</html>
