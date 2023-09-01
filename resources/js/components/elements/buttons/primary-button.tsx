import Button, {ButtonProps} from "@/components/elements/buttons/button";

export default function PrimaryButton(props: ButtonProps) {
    return (
        <Button {...props} color={'primary'} />
    );
}
