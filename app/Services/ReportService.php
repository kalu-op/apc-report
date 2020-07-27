<?php
namespace App\Services;

use App\Http\Requests\GetReportsRequest;
use App\Http\Resources\ReportResource;
use App\Report;
use App\User;
use Illuminate\Validation\ValidationException;

class ReportService
{
    private ?User $user = null;
    private ?GetReportsRequest $request = null;

    const FILTER_AUTHORIZED = [
        'CUSTOMER_NAME' => 'customer',
        'CUSTOMER_ID' => 'customer_id',
        'COMPLETED_STATUS' => 'completed',
        'APPOINTMENT_START_AT' => 'start_at',
    ];

    public function __construct(GetReportsRequest $request)
    {
    }

    public function setUserRequest($request) {
        // handle validations
        $validated = $request->validated();
        $hasValidParams = $this->handleWrongParameters();

        if ($hasValidParams === true) {
            $this->request = $request;
            $this->user = $request->user();
        }
    }

    /**
     * Permet de gérer les valeurs GET inconnus/interdis passé à by
     */
    private function handleWrongParameters()
    {
        if (isset($this->request->by) && in_array($this->request->by, self::FILTER_AUTHORIZED) === false) {
            $error = ValidationException::withMessages([
                'by' => ['filtre inconnu']
            ]);
            throw $error;
        }

        return true;
    }

    /**
     * Récupère le report via son ID
     */
    public function getById(int $id)
    {
        if (empty($id)) {
            throw ValidationException::withMessages([
                'id' => ['id report introuvable']
            ]);
        }

        $result = Report::find($id);
        return new ReportResource($result);
    }

    /**
     * GET les reports
     */
    private function getAll()
    {
        $result = Report::query()
            ->where('reports.user_id', '=', $this->user->id)
            ->offset($this->request->offset)
            ->limit($this->request->limit)
            ->orderBy('created_at', 'desc')
            ->get();

        return ReportResource::collection($result);
    }

    /**
     * GET les reports par le nom du client
     */
    private function getByCompletedStatus()
    {
        $result = Report::query()
            ->where('reports.user_id', '=', $this->user->id)
            ->where('reports.is_complete', '=', $this->request->value)
            ->offset($this->request->offset)
            ->limit($this->request->limit)
            ->get();

        dd($result);


        return ReportResource::collection($result);
    }

    /**
     * GET les reports par le nom du client
     */
    private function getByCustomerName()
    {
        $result = Report::query()
            ->join('customers', 'reports.customer_id', '=', 'customers.id')
            ->where('reports.user_id', '=', $this->user->id)
            ->where('customers.name', 'LIKE', '%' . $this->request->value . '%')
            ->offset($this->request->offset)
            ->limit($this->request->limit)
            ->get('reports.*');

        return ReportResource::collection($result);
    }

    /**
     * GET les reports par l'ID du client
     */
    private function getByCustomerId()
    {
        $result = Report::query()
            ->where('customer_id', '=', $this->request->value)
            ->where('reports.user_id', '=', $this->user->id)
            ->offset($this->request->offset)
            ->limit($this->request->limit)
            ->get();

        return ReportResource::collection($result);
    }

    /**
     * GET les reports par la date start de l'appointment associé
     */
    private function getByAppointmentDateStart() {
        $result = Report::query()
            ->join('appointments', 'appointments.id', '=', 'reports.appointment_id')
            ->where('reports.user_id', '=', $this->user->id)
            ->whereDate('appointments.start_at', '=', $this->request->value)
            ->offset($this->request->offset)
            ->limit($this->request->limit)
            ->get('reports.*');

        return ReportResource::collection($result);
    }

    /**
     * UPDATE report
     */
    function updateById($id)
    {
        $report = Report::find($id);

        $report->is_complete = true;
        $report->responses = $this->request->responses;

        $report->save();

        // succes response with report updated
        return response()->reportUpdated($report);
    }

    /**
     *
     */
    public function handleFilteredRequest()
    {
        switch ($this->request->by) {
            case self::FILTER_AUTHORIZED['COMPLETED_STATUS'] :
                return $this->getByCompletedStatus();
                break;
            case self::FILTER_AUTHORIZED['CUSTOMER_NAME'] :
                return $this->getByCustomerName();
                break;
            case self::FILTER_AUTHORIZED['CUSTOMER_ID'] :
                return $this->getByCustomerId();
                break;
            case self::FILTER_AUTHORIZED['APPOINTMENT_START_AT'] :
                return $this->getByAppointmentDateStart();
                break;
            default:
                return $this->getAll();
                break;
        }
    }
}