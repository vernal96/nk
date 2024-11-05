export const SocNet = {
    name: "Socnet",
    props: {
        data: {
            type: Object,
            required: true
        }
    },
    template: `
		<div class="social-network" v-if="data">
		  <a
		  v-for="(url, name) in data" 
		  :href="url"
		  rel="nofollow"
		  target="_blank"
		  class="social-network__item"
		  :class="'social-network__item--' + name"
		  ></a>
		</div>
	`
}