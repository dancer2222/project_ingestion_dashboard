<template>
	<div class="col-12">
		<div class="row">
			<div class="col-12 col-sm-12">
				<div class="alert alert-danger" role="alert" v-if="isError" v-html="errorMessage"></div>

				<div class="card">
					<div class="card-header">
						<button class="btn btn-outline-dark float-left mr-3" v-on:click="$router.back()">Back</button>

						<strong>Album</strong>

						{{album.title}} [id:{{album.id}}]
						
						<button class="btn btn-outline-info float-right" v-on:click="editAlbum">edit</button>
					</div>

					<div class="card-body" v-if="album">
						<div class="row">
							<div class="card_info_wrapper col-sm-12 col-lg-6">
								<h5 class="mb-4">
									<b>
										Show on prodution this album -
										<a href="#" target="_blank">{{ album.title }}</a>
									</b>
								</h5>

								<div class="card border-success" v-bind:class="[album.status == 'active' ? 'border-success' : 'border-danger']">
									<div class="row" v-if="album">
										<div class="image_wrapper">
											<span class="status" v-bind:class="[album.status == 'active' ? 'badge-success' : 'badge-danger']">
												{{album.status}}
											</span>
											<img
												v-if="album.num_of_images > 0"
												v-bind:src="pictureURL"
												alt="audiobook cover"
											/>
											<img
												v-else
												v-bind:src="pictureError"
												v-bind:width="200"
												v-bind:height="200"
												alt="No picture available"
											/>
										</div>

										<p class="card-title col-12">
											<button @click="zeroShow=!zeroShow" class="btn btn-sm btn-outline-dark" >
												{{ zeroShow ? "Hide null values" : "Show null values" }}
											</button>
											<button @click="descriptionShow=!descriptionShow" class="btn btn-sm btn-outline-dark" >
												description
											</button>
										</p>
										<p class="card-title col-12">
											<b>
												<div v-show="descriptionShow">
													{{ album.description }}
												</div>
											</b>
										</p>
										<p class="card-title col-12" v-for="(value, title) in album">

											<b v-show="zeroShow" 
												v-if="value == 0 && 
													value == '' &&
													value !== null">
												<span class="title">{{ title }}</span> - {{ value }}
											</b>

											<b v-if="title !== 'seq_id' && 
													title !== 'description' &&  
													value !== 0 && 
													value !== '' && 
													value !== null && 
													!Array.isArray(value)">
												<span class="title">{{ title }}</span> - {{ value }}
											</b>

											<b v-if="Array.isArray(value) && value.length > 0">
												<span class="title">{{ title }}</span>:
												<span v-for="val in value">
													{{ val.name }} [{{ val.id }}] <span class="green">{{ val.status }}</span>
													<br />
												</span>
											</b>
										</p>
									</div>
								</div>
							</div>

							<div class="card_tabs_wrapper col-sm-12 col-lg-6">
								<h5 class="mb-4">
									<b>
										Show on QA this album -
										<a href="#" target="_blank">{{ album.title }}</a>
									</b>
								</h5>

								<div class="card">
									<div class="card-body p-b-0">
										<ul class="nav nav-tabs customtab2" role="tablist">
											<li class="nav-item">
												<a class="nav-link active show"
													data-toggle="tab"
													href="#georestricts"
													role="tab"
													aria-selected="true">
													<span class="hidden-xs-down">Geo Restricts</span>
												</a>
											</li>

											<li class="nav-item">
												<a class="nav-link show"
													data-toggle="tab"
													href="#siterestricts"
													role="tab"
													aria-selected="false">
													<span class="hidden-xs-down">Site Restricts</span>
												</a>
											</li>

											<li class="nav-item">
												<a class="nav-link"
													data-toggle="tab"
													href="#status_info"
													role="tab"
													aria-selected="false">
													<span class="hidden-xs-down">Status info</span>
												</a>
											</li>

											<li class="nav-item hidden">
												<a class="nav-link"
													data-toggle="tab"
													href="#failed_items"
													role="tab"
													aria-selected="false">
													<span class="hidden-xs-down">Failed items</span>
												</a>
											</li>

											<li class="nav-item hidden">
												<a class="nav-link"
													data-toggle="tab"
													href="#tracks"
													role="tab"
													aria-selected="false">
													<span class="hidden-xs-down">Tracks</span>
												</a>
											</li>
										</ul>

										<div class="tab-content">
											<div class="tab-pane active show" id="georestricts" role="tabpanel">
												<b>Active --</b>
												<span class="text-secondary">UM</span>
												<span class="text-danger font-weight-bold">US</span>

												<hr />

												<b>Inactive --</b>
											</div>
											<div class="tab-pane" id="status_info" role="tabpanel">
												<table class="table table-hover">
													<thead class="thead-dark">
														<tr>
															<th scope="col">#</th>
															<th scope="col">From</th>
															<th scope="col">To</th>
															<th scope="col">Date added</th>
														</tr>
													</thead>

													<tbody>
														<tr>
															<th scope="row">38391626</th>
															<td>inactive</td>
															<td>active</td>
															<td>2017-06-30 13:42:57</td>
														</tr>

														<tr>
															<th scope="row">38380697</th>
															<td>active</td>
															<td>inactive</td>
															<td>2017-06-30 12:45:04</td>
														</tr>
													</tbody>
												</table>
											</div>
											<div class="tab-pane" id="failed_items" role="tabpanel">
												<table class="table table-hover">
													<tbody></tbody>
												</table>
											</div>
											<div class="tab-pane" id="siterestricts" role="tabpanel">
												<table class="table table-hover">
													<tbody></tbody>
												</table>
											</div>
											<div class="tab-pane" id="tracks" role="tabpanel">
												<tracks :tracks="tracks" />
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
	import axios from "axios";
	import tracks from "./Tracks";

	export default {
		components: { tracks },
		debug: true,
		name: "albumsDataView",
		data: function() {
			return {
				isError: false,
				errorMessage: 'Something went wrong. Please try reloading the <a href="javascript:location.reload();">page</a>',
				album: {},
				mediaType: "albums",
				pictureError: "img/image-not-found.jpeg",
				pictureURL: "",
				descriptionShow: false,
				zeroShow: false,
				tracks: {}
			};
		},
		methods: {
			fetchData: function() {
				let self = this;
				axios.get(this.mediaType + "/" + this.$route.params.id)
					.then(function(response) {
						if (Object.keys(response.data).length > 0) {
							self.album = response.data;
							self.pictureURL = self.album.coverUrl;
							Vue.delete(self.album, "coverUrl");
							self.tracks = self.album.tracks;
							Vue.delete(self.album, "tracks");
							console.log(self.album.tracks);
						} else {
							self.isError = true;
						}
					}).catch(function(error) {
						self.isError = true;
				});
			},
			editAlbum: function(e) {
				this.$router.push({
					name: "albums_edit",
					path: "album",
					params: { album: this.album }
				});
			}
		},
		mounted() {
			console.log("album component");
			this.fetchData();
		}
	};
</script>