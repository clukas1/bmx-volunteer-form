<?php

readonly class Input implements FormItem {

	private string $label;
	private int $number_volunteers;
	/**
	 * @var Volunteer[]
	 */
	private array $volunteers;

	/**
	 * @var int[]
	 */
	private array $indexes;

	/**
	 * @param array $data
	 * @param int[] $indexes
	 */
	public function __construct( array $data, array $indexes ) {
		$this->set_label( $data['label'] );
		$this->set_number_volunteers( $data['numberVolunteers'] );
		$this->set_volunteers( $data['registeredVolunteers'] ?? [] );
		$this->indexes = $indexes;
	}

	public function render(): void {
		?>
        <div class="row mb-2 border-bottom">
            <div class="col-12 col-sm-4 py-2">
                <div class="d-flex justify-content-center justify-content-sm-start">
                    <div>
	                    <?php echo $this->get_label(); ?>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-8 py-2">
                <div class="d-flex justify-content-center justify-content-sm-start">
	                <?php $this->render_volunteer_fields(); ?>
                </div>
            </div>
        </div>
		<?php
	}

	private function render_volunteer_fields(): void {
		for ( $i = 0; $i < $this->get_number_volunteers(); $i ++ ) {
			$this->render_volunteer_field( $i );
		}
	}

	private function render_volunteer_field( int $i ): void {
		$class = '';
        if ( $i < count( $this->get_volunteers() ) ) {
			$status = 'set';
            $class = 'is-valid';
		} elseif ( $i === count( $this->get_volunteers() ) ) {
			$status = 'next';
		} else {
			$status = 'future';
		}

		$formatted_index = join( '_', $this->indexes );

		?>
        <div class="px-2">
            <input
                    class="form-check-input <?php echo $class; ?>"
                    type="checkbox"
                    name="shift_<?php echo $formatted_index; ?>"
				<?php echo $status === 'set' ? ' checked' : ''; ?>
				<?php echo $status !== 'next' ? ' disabled' : ''; ?>
            />
        </div>
		<?php
	}

	public function get_label(): string {
		return $this->label;
	}

	public function set_label( string $label ): void {
		$this->label = $label;
	}

	public function get_number_volunteers(): int {
		return $this->number_volunteers;
	}

	public function set_number_volunteers( int $number_volunteers ): void {
		$this->number_volunteers = $number_volunteers;
	}

	/**
	 * @return Volunteer[]
	 */
	public function get_volunteers(): array {
		return $this->volunteers;
	}

	public function set_volunteers( array $volunteers ): void {
		$this->volunteers = array_map( function ( $volunteer ) {
			return new Volunteer( $volunteer );
		}, $volunteers );
	}
}