<div class="content">
	<div class="container-fluid">
		<br /> <br />
		<div class="row">
			<div class="col-md-3"></div>
			<div class="col-md-6">
				<div class="card-box">
					<h4 class="header-title m-t-0 text-center">Add new complaint</h4>
					<br />
					<form class="" action="/complaints/new/" method="post">
						<?php
						if ($success)
						{
							?>
							<div class="alert alert-success alert-dismissible fade show" role="alert">Complaint has been added
								<button type="button" class="close" data-dismiss="alert" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<?php
						}
						if (isset($errorList) && count($errorList))
						{
							foreach ($errorList as $error)
							{
								?>
								<div class="alert alert-danger alert-dismissible fade show" role="alert"><?= $error; ?>
									<button type="button" class="close" data-dismiss="alert" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<?php
							}
						}
						?>
						<div class="form-group">
							<label>Complaint Title</label>
							<input name='title' value="<?=Input::get('title');?>" type="text" class="form-control" required placeholder="Enter your title"/>
						</div>
						<div class="form-group">
							<label>Message</label>
							<div>
								<textarea name="message" required class="form-control"><?=Input::get('message');?></textarea>
							</div>
						</div>
						<div class="form-group">
							<div>
								<button type="submit" class="btn btn-primary waves-effect waves-light">
									Add
								</button>
								<button type="reset" class="btn btn-secondary waves-effect m-l-5">
									Cancel
								</button>
							</div>
						</div>
					</form>
				</div>
			</div>
			<div class="col-md-3"></div>
		</div>
	</div>
</div>