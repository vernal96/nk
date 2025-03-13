import {template} from "./template";
import {component as Auth} from "./auth/component";
import {component as FGP} from "./fgp/component";
export const Authorize = {
    name: "Authorize",
    data: () => ({
        form: Auth,
    }),
    components: {
        Auth,
        FGP
    },
    template: template
};