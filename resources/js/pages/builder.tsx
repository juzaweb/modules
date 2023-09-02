import Admin from "../layouts/admin";
import ElementBuilder from "../components/ElementBuilder";

export default function Dashboard({ builder }: any) {
    return (
        <Admin>
            <ElementBuilder builder={builder} />
        </Admin>
    );
}
