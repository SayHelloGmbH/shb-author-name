import { registerBlockType } from "@wordpress/blocks";

import Edit from "./edit";

registerBlockType("shb/author-name", {
	edit: Edit,
});
