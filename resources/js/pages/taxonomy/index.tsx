import DataTable from "@/components/elements/data-table";

export default function Index({ dataTable }) {
    return (
        <>
            <DataTable config={dataTable} />
        </>
    );
}
