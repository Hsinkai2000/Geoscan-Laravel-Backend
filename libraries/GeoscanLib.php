<?php

namespace Libraries;

use App\Models\NoiseDevice;
use App\Models\Concentrator;

define("MESSAGE_TYPES", array(0x00, 0x01, 0x02, 0x03, 0x04));

class GeoscanLib
{
    protected $device_id;
    protected $summary;
    protected $data;
    protected $crc32;
    protected $sound;

    public function __construct($attributes = [])
    {
        $this->device_id = $attributes['device_id'];
        $this->summary = $attributes['summary'];
        $this->data = $attributes['data'];
        $this->crc32 = $attributes['crc32'];
        $this->sound = $attributes['sound'];
    }

    public function params_not_valid()
    {
        return empty($this->device_id) || empty($this->crc32) || empty($this->summary) || empty($this->data);
    }

    public function crc32_valid()
    {
        return true;
    }

    public function message_types()
    {
        return MESSAGE_TYPES;
    }

    public function message_type()
    {
        return unpack("I", $this->summary)[0];
    }

    public function concentrator_id()
    {
        $unpacked_device_id = unpack("II", $this->device_id);
        return $unpacked_device_id[0] . $unpacked_device_id[1];
    }

    public function concentrator($id)
    {
        $concentrator = Concentrator::find($id)->first();
        if (!empty($concentrator)) {
            return $concentrator;
        }
        return null;
    }

    public function noise_serial_number()
    {
        $data_pack = pack("V", [($this->summary_values())[2]]);

        $serial = null;

        if (($this->summary_values())[2] > 0x00FFFFFF) {
            $data_array = unpack("CCCA", $data_pack);
            $str_part = $data_array[3];
            $int_packed = pack("CCCC", [$data_array[0], $data_array[1], $data_array[2], 0]);
            $int_part = unpack("V", $int_packed)[0];

            $serial = "BJ" . $str_part . $int_part;

        } else {
            $serial = strval(($this->summary_values())[2]);
        }

        return $serial;
    }

    public function unpack_format()
    {
        switch ($this->message_type()) {
            case MESSAGE_TYPES[0]:
                return "VVvv";
            case MESSAGE_TYPES[1]:
                return "VVVVvv";
            // case MESSAGE_TYPES[2]:
            //     return "VVvvLLCCCCSCCCCSCCCCffffCCCCCCCCfffVVV";
            // case MESSAGE_TYPES[3]:
            //     return "VLLC";
            // case MESSAGE_TYPES[4]:
            //     return "VVvvLLCAAAAAAAAAAAAAAAAAAAAASCCCCCCSCCCCCCS";
            default:
                return null;
        }


    }

    public function noise_device($noise_serial_number)
    {
        return NoiseDevice::where('serial_number', $noise_serial_number)->first();

    }

    public function noise_data_value()
    {
        return unpack("f", $this->data);
    }


    public function summary_values()
    {
        return unpack($this->unpack_format(), $this->summary);
    }

}