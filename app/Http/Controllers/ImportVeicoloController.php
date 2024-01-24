<?php

namespace App\Http\Controllers;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\ImportVeicolo;
use Illuminate\Http\Request;

class ImportVeicoloController extends Controller
{
	private static function isAnagraficaRowValid(array $rowData)
	{
		$requiredColumns = [
			'targa',
			'telaio',
			'societa',
			'tipologia_veicolo',
			'allestimento',
			'marca',
			'modello',
			'colore',
			'lunghezza',
			'larghezza',
			'massa',
			'portata',
			'cilindrata',
			'potenza',
			'numero_assi',
			'tipo_asse',
			'tipo_cambio',
			'alimentazione',
			'data_immatricolazione',
			'destinazione_uso',
			];
		//check data integrity

		foreach ($requiredColumns as $column) {
			if (!array_key_exists($column, $rowData)) {
				return false;
			}
		}

		return true;
	}

	private static function getAnagraficaFromWorksheet(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $worksheet)
	{
		$anagrafica = [];

		$rowCount = 0;
		$columnMapping = [
			'A' => 'targa',
			'B' => 'telaio',
			'C' => 'societa',
			'D' => 'tipologia_veicolo',
			'E' => 'allestimento',
			'F' => 'marca',
			'G' => 'modello',
			'H' => 'colore',
			'I' => 'lunghezza',
			'J' => 'larghezza',
			'K' => 'massa',
			'L' => 'portata',
			'M' => 'cilindrata',
			'N' => 'potenza',
			'O' => 'numero_assi',
			'P' => 'tipo_asse',
			'Q' => 'tipo_cambio',
			'R' => 'alimentazione',
			'S' => 'data_immatricolazione',
			'T' => 'destinazione_uso',
		];

		foreach ($worksheet->getRowIterator() as $row) {
			$rowCount++;
			if ($rowCount == 1) {
				continue;
			}

			$cellIterator = $row->getCellIterator();
			$cellIterator->setIterateOnlyExistingCells(FALSE);

			$rowData = [];
			foreach ($cellIterator as $cell) {
				$columnLetter = $cell->getColumn(); // Get the column letter (e.g., 'A', 'B', 'C', ...)

				if(array_key_exists($columnLetter,$columnMapping)) {

					$columnName = $columnMapping[$columnLetter]; // Map the column letter to the desired key
					if ($columnName === 'data_immatricolazione') {
						$dateValue = $cell->getValue();
						$dateFormat = $cell->getStyle()->getNumberFormat()->getFormatCode();

						if ($dateFormat === 'dd/mm/yyyy') {
							// The cell contains a date in the "dd/mm/yyyy" format
							$rowData[$columnName] = $dateValue;
						} else {
							// Convert the serial date value to a readable date format
							$date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToTimestamp($dateValue);
							$formattedDate = date('d/m/Y', $date);
							$rowData[$columnName] = $formattedDate;
						}
					} else {
						$rowData[$columnName] = $cell->getValue(); // Assign the cell value to the corresponding key in $rowData
					}
				}

			}

			if(self::isAnagraficaRowValid($rowData)) {
				continue;
			}

			$anagrafica[($rowCount-1)] = $rowData;
		}

		return $anagrafica;
	}


	/****************** Standard web methods ***************/

	public function index() { /* Return view with all Imports */
		$import_veicolo = ImportVeicolo::all();
		return view('import_veicolo.index', compact('import_veicolo'));
	}

	public function create() { /* Return view to create a new post */
		return view('import_veicolo.create');
	}

	public function store(Request $request)
	{ /* Store new Imported Data */
		//read the file excelFile and checks if it is valid
		$validatedFile = ImportVeicolo::validateExcel($request);

		$spreadsheet = IOFactory::load($validatedFile->getPathName());
		$worksheets = $spreadsheet->getAllSheets();

		foreach ($worksheets as $worksheet) { //loop through worksheets
			//get the name of the worksheet
			$worksheetTitle = $worksheet->getTitle();
			switch ($worksheetTitle) {
				case('ANAGRAFICA FLOTTA'):
					{
						$anagrafica = self::getAnagraficaFromWorksheet($worksheet);
						self::storeAnagrafica($anagrafica);
						break;
					}
				case('SCADENZE'):
					{
						$scadenze = self::getScadenzeFromWorksheet($worksheet);
						break;
					}
				default:
					{
						break;
					}
			}


			return back()->with('success', 'File imported successfully');
		}

	}

	public function show(ImportVeicolo $importVeicolo) { /* Return view with a single import_veicolo */
		return view('import_veicolo.show', compact('import_veicolo'));
	}

	public function edit(ImportVeicolo $importVeicolo) { /* Return view to edit a import_veicolo */
		return view('import_veicolo.edit', compact('import_veicolo'));
	}

	public function update(Request $request, ImportVeicolo $importVeicolo) { /* Update a import_veicolo */
		$validatedData = ImportVeicolo::validate($request);
		$importVeicolo->update($validatedData);

		return redirect()->route('import_veicolo.index');
	}

	public function destroy(ImportVeicolo $importVeicolo) { /* Delete a import_veicolo */
		$importVeicolo->delete();
		return redirect()->route('import_veicolo.index');
	}

	/****************** API methods ***************/
	public function apiIndex() {
		/* Return all import_veicolo as JSON for API calls */
		$import_veicolo = ImportVeicolo::all();
		return $import_veicolo->toJson();
	}

	public function apiShow(ImportVeicolo $importVeicolo) {
		/* Return a single ImportVeicolo as JSON */
		return $importVeicolo->toJson();
	}

	/****************** Helper methods ***************/

	private static function readExcel ($excelFile) {
		//reads the data of $excelFile with phpoffice/phpspreadsheet and returns an array

		$excelData = ImportVeicolo::readExcel($excelFile);
		return $excelData;
	}
private static function storeAnagrafica($anagrafica) {
		foreach ($anagrafica as $data) {
			$importVeicolo = new ImportVeicolo();
			$importVeicolo->targa = $data['targa'];
			$importVeicolo->telaio = $data['telaio'];
			$importVeicolo->societa = $data['societa'];
			$importVeicolo->tipologia_veicolo = $data['tipologia_veicolo'];
			$importVeicolo->allestimento = $data['allestimento'];
			$importVeicolo->marca = $data['marca'];
			$importVeicolo->modello = $data['modello'];
			$importVeicolo->colore = $data['colore'];
			$importVeicolo->lunghezza = $data['lunghezza'];
			$importVeicolo->larghezza = $data['larghezza'];
			$importVeicolo->massa = $data['massa'];
			$importVeicolo->portata = $data['portata'];
			$importVeicolo->cilindrata = $data['cilindrata'];
			$importVeicolo->potenza = $data['potenza'];
			$importVeicolo->numero_assi = $data['numero_assi'];
			$importVeicolo->tipo_asse = $data['tipo_asse'];
			$importVeicolo->tipo_cambio = $data['tipo_cambio'];
			$importVeicolo->alimentazione = $data['alimentazione'];
			$importVeicolo->data_immatricolazione = $data['data_immatricolazione'];
			$importVeicolo->destinazione_uso = $data['destinazione_uso'];

			// Save the record to the database
			$importVeicolo->save();
		}
	}

}
