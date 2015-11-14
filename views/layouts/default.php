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
      th .data {
        min-width: 90px;
      }
    </style>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
	  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.min.css" rel="stylesheet">
    <script src="http://code.jquery.com/jquery.js"></script>
		<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
	  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
		<link href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/css/select2.min.css" rel="stylesheet" />
		<script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/js/select2.min.js"></script>
		<script src="<?php echo HTMLROOT; ?>js/bootstrap-slider.js"></script>
		<link rel="stylesheet" href="<?php echo HTMLROOT; ?>css/bootstrap-slider.css">
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
  		    filter: $('#filter').val(),
			headers: $('#headers').val()
  	      }, function(data){
  		    $('#ajaxtable').replaceWith(data);
  		  });
      }

      /**
       * jQuery on ready
       */
      $(function() {
	  
		$(document).on("click", "#button-apply-options", function() {
			var showColumns = [];
			$('#show-columns li').each(function (i, element) {
				showColumns.push($(element).data('id'));
			});
			showColumns = showColumns.join(',');
			console.log(showColumns);
			$('#headers').val(showColumns);
			refreshTable();
		}); 


    	/**
         * Create event handler when sortable headers are clicked
         *
         * When a sortable header is clicked, the class indicating sort direction is updated,
         * the hidden sorting fields changed, and the table refreshed
         */  
		$(document).on("click", ".sortable", function() {
		  attribute = $(this).data('sort-attribute');
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
          $('#delete-modal').modal('show');
        }); 

				$('.datepicker').datepicker({
					orientation: 'top'
				});
				
				$('.select2').select2({
					width: 'resolve'
				});
				
				$('.select2-tags').select2({
					tags: true,
					width: 'resolve'
				});
				
				$('.select2-user-input').select2({
					tags: true,
					createTag: function (params) {
						return {
							id: params.term,
							text: params.term,
							newOption: true
						}
					},
					templateResult: function (data) {
						var $result = $("<span></span>");

						$result.text(data.text);

						if (data.newOption) {
							$result.append(" <em>(new)</em>");
						}

						return $result;
					},
					width: 'resolve'
				});
				
				
		
      });
    </script>
  </head>
  <body>
    <div class="navbar navbar-default navbar-fixed-top">
      <div class="container">
	    <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" id="nav-item-home" href="<?php echo HTMLROOT?>">Test Project</a>
		</div>
        <div class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
			<li class="dropdown">
			  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Manage <span class="caret"></span></a>
			    <ul class="dropdown-menu">
				  <li id="nav-item-inventory"><a href="<?php echo HTMLROOT?>items">Inventory</a>
				  <li id="nav-item-statuses"><a href="<?php echo HTMLROOT?>statuses">Statuses</a>
				  <li id="nav-item-vendors"><a href="<?php echo HTMLROOT?>vendors">Vendors</a>
				  <li id="nav-item-tags"><a href="<?php echo HTMLROOT?>tags">Tags</a>
				  <li id="nav-item-alerts"><a href="<?php echo HTMLROOT?>alerts">Alerts</a>
			    </ul>
			</li>
		  </ul>
		  <ul class="nav navbar-nav navbar-right">
			<?php if ($user = Auth::User()): ?>
				<li id="nav-item-logout"><a href="<?php echo HTMLROOT?>users/logout">Logout</a>
			<?php else: ?>
				<li id="nav-item-login"><a href="<?php echo HTMLROOT?>users/login">Login</a>
			<?php endif; ?>
		  </ul>
        </div>
      </div>
    </div>
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
