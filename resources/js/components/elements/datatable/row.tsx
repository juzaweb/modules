import {DatatableColumn, DatatableProps} from "../../../types/datatable";
import React, {useEffect, useState} from "react";

export default function Row({table, row, checked}: {table: DatatableProps, row: any, checked?: boolean}) {
    const [isChecked, setIsChecked] = useState(checked);

    const handleChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        setIsChecked(e.target.checked);
    }

    useEffect(() => {
        setIsChecked(checked);
    }, [checked]);

    return <tr>
        {table.actions && table.actions.length > 0 && (
            <td>
                <input
                    type="checkbox"
                    name={'ids[]'}
                    className={'jw-checkbox'} value={row.id}
                    checked={isChecked}
                    onChange={handleChange}
                />
            </td>
        )}

        {table.columns.map((column: DatatableColumn, index: number) => {
            if (table.escapes.includes(column.key)) {
                return <td key={index}>{row[column.key]}</td>;
            }

            return <td key={index} dangerouslySetInnerHTML={{__html: row[column.key]}}></td>;
        })}
    </tr>;
}