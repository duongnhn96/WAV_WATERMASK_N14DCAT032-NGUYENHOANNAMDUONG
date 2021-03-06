<?php 
ini_set('memory_limit', '-1');
ini_set('max_execution_time', 180);

class MyMusic
{
	
	function showMusic()
	{
		$kn= mysqli_connect("localhost","root","","musicbyduong");
		$sql="select * from music where nguoiSoHuu='admin' ";
		$kq= mysqli_query($kn,$sql);
		return $kq;
	}
	function checkListBuy($id,$user)
	{
		$kn= mysqli_connect("localhost","root","","musicbyduong");
		$sql="select id from music where fieldspr='$id' and nguoiSoHuu = '$user' ";
		$kq= mysqli_fetch_row(mysqli_query($kn,$sql));
		return $kq;
	}
	function showListDaMua($user)
	{
		$kn= mysqli_connect("localhost","root","","musicbyduong");
		$sql="select * from music where  nguoiSoHuu = '$user' ";
		$kq= mysqli_query($kn,$sql);
		return $kq;
	}
	function checkusername($user){
		$kn= mysqli_connect("localhost","root","","musicbyduong");
		$sql="select user from taikhoan where user = '$user' ";
		$kq= mysqli_fetch_row(mysqli_query($kn,$sql));
		return $kq;
		
	}
	function insertuser($user,$Matkhau){
		$kn= mysqli_connect("localhost","root","","musicbyduong");
		$sqlinsert="insert into taikhoan(user,pass) values('$user','$Matkhau')";
		$kqinsert=mysqli_query($kn,$sqlinsert);
		return $kqinsert;
	}
	function checkuser($user,$pass){
		$sql = "select user,pass,role from taikhoan where user='$user' and pass='$pass' ";
		$kn= mysqli_connect("localhost","root","","musicbyduong");
		$kq= mysqli_query($kn,$sql);
		return $kq;
	}


}

class WavFile{
	private static $HEADER_LENGTH = 44;

	public static function ReadFile($filename) {
		$filesize = filesize($filename);
		if ($filesize<self::$HEADER_LENGTH)
			return false;           
			$handle = fopen($filename, 'rb');
			$wav = array(
				'header'    => array(
					'chunkid'       => self::readString($handle, 4),
					'chunksize'     => self::readLong($handle),
					'format'        => self::readString($handle, 4)
				),
				'subchunk1' => array(
					'id'            => self::readString($handle, 4),
					'size'          => self::readLong($handle),
					'audioformat'   => self::readWord($handle),
					'numchannels'   => self::readWord($handle),
					'samplerate'    => self::readLong($handle),
					'byterate'      => self::readLong($handle),
					'blockalign'    => self::readWord($handle),
					'bitspersample' => self::readWord($handle)
				),
				'subchunk2' => array( 
					'id'            => self::readString($handle, 4),
					'size'			=> self::readLong($handle),
					'data'          => null
				),
				'subchunk3' => array(
					'id'			=> null,
					'size'			=> null,
					'data'          => null
				)
			);
			$wav['subchunk2']['data'] = fread($handle, $wav['subchunk2']['size']);
			$wav['subchunk3']['id'] = self::readString($handle, 4);
			$wav['subchunk3']['size'] = self::readLong($handle);
			$wav['subchunk3']['data'] = fread($handle, $wav['subchunk3']['size']);
			fclose($handle);
			return $wav;
	}


		private static function readString($handle, $length) {
			return self::readUnpacked($handle, 'a*', $length);
		}

		private static function readLong($handle) {
			return self::readUnpacked($handle, 'V', 4);
		}

		private static function readWord($handle) {
			return self::readUnpacked($handle, 'v', 2);
		}

		private static function readUnpacked($handle, $type, $length) {
			$r = unpack($type, fread($handle, $length));
			return array_pop($r);
		}
		private static function writeString($handle, $string) {
			self::writePacked($handle, 'a*', $string);
		}

		private static function writeLong($handle, $string) {
			self::writePacked($handle, 'V', $string);
		}

		private static function writeWord($handle, $string) {
			self::writePacked($handle, 'v', $string);
		}

		private static function writePacked($handle, $type, $string) {
			fwrite($handle, pack($type, $string));
		}

	  public static function WriteFile($wav, $filename) {
            $handle = fopen($filename, 'wb');
            self::writeString($handle, $wav['header']['chunkid']);
            self::writeLong($handle, $wav['header']['chunksize']);
            self::writeString($handle, $wav['header']['format']);
            self::writeString($handle, $wav['subchunk1']['id']);
            self::writeLong($handle, $wav['subchunk1']['size']);
            self::writeWord($handle, $wav['subchunk1']['audioformat']);
            self::writeWord($handle, $wav['subchunk1']['numchannels']);
            self::writeLong($handle, $wav['subchunk1']['samplerate']);
            self::writeLong($handle, $wav['subchunk1']['byterate']);
            self::writeWord($handle, $wav['subchunk1']['blockalign']);
            self::writeWord($handle, $wav['subchunk1']['bitspersample']);
            self::writeString($handle, $wav['subchunk2']['id']);
            self::writeLong($handle, $wav['subchunk2']['size']);
            fwrite($handle, $wav['subchunk2']['data']);
            self::writeString($handle, $wav['subchunk3']['id']);
            self::writeLong($handle, $wav['subchunk3']['size']);
            fwrite($handle, $wav['subchunk3']['data']);
            fclose($handle);
	    }
	 

	}
	?>