<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Models\User;
use Carbon\Carbon;

class PermanentDeleteAccount extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:account';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Permanently delete an account and its data';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            $restoreUserRelations = User::onlyTrashed()->where('deleted_at', '<', Carbon::now()->subDays(30))->first();

            if ($restoreUserRelations != null) {
                $restoreUserRelations->Mission()->onlyTrashed()->each(function ($q1) {
                    $q1->MissionContent()->onlyTrashed()->each(function ($q2) {
                        $q2->forceDelete();
                    });
                    $q1->Comment()->onlyTrashed()->each(function ($q2) {
                        $q2->Like()->onlyTrashed()->each(function ($q3) {
                            $q3->forceDelete();
                        });

                        $q2->forceDelete();
                    });
                    $q1->Report()->onlyTrashed()->each(function ($q2) {
                        $q2->forceDelete();
                    });
                    $q1->Respone()->onlyTrashed()->each(function ($q2) {
                        $q2->ResponseContent()->onlyTrashed()->each(function ($q3) {
                            $q3->forceDelete();
                        });
                        $q2->Like()->onlyTrashed()->each(function ($q3) {
                            $q3->forceDelete();
                        });
                        $q2->Comment()->onlyTrashed()->each(function ($q3) {
                            $q3->Like()->onlyTrashed()->each(function ($q4) {
                                $q4->forceDelete();
                            });

                            $q3->forceDelete();
                        });
                        $q2->Report()->onlyTrashed()->each(function ($q3) {
                            $q3->forceDelete();
                        });
                        $q2->Tags()->onlyTrashed()->each(function ($q3) {
                            $q3->forceDelete();
                        });
                        $q2->GradeOverview()->onlyTrashed()->each(function ($q3) {
                            $q3->forceDelete();
                        });

                        $q2->forceDelete();
                    });
                    $q1->Tags()->onlyTrashed()->each(function ($q2) {
                        $q2->forceDelete();
                    });
                    $q1->QuickScores()->onlyTrashed()->each(function ($q2) {
                        $q2->forceDelete();
                    });
                    $q1->ActiveTimer()->onlyTrashed()->each(function ($q2) {
                        $q2->forceDelete();
                    });
                    $q1->Like()->onlyTrashed()->each(function ($q2) {
                        $q2->forceDelete();
                    });
                });
                $restoreUserRelations->ClassRoom()->onlyTrashed()->each(function ($q1) {
                    $q1->Mission()->onlyTrashed()->each(function ($q2) {
                        $q2->MissionContent()->onlyTrashed()->each(function ($q3) {
                            $q3->forceDelete();
                        });
                        $q2->Comment()->onlyTrashed()->each(function ($q3) {
                            $q3->Like()->onlyTrashed()->each(function ($q4) {
                                $q4->forceDelete();
                            });

                            $q3->forceDelete();
                        });
                        $q2->Report()->onlyTrashed()->each(function ($q3) {
                            $q3->forceDelete();
                        });
                        $q2->Respone()->onlyTrashed()->each(function ($q3) {
                            $q3->ResponseContent()->onlyTrashed()->each(function ($q4) {
                                $q4->forceDelete();
                            });
                            $q3->Like()->onlyTrashed()->each(function ($q4) {
                                $q4->forceDelete();
                            });
                            $q3->Comment()->onlyTrashed()->each(function ($q4) {
                                $q4->Like()->onlyTrashed()->each(function ($q5) {
                                    $q5->forceDelete();
                                });
                                $q4->forceDelete();
                            });
                            $q3->Report()->onlyTrashed()->each(function ($q4) {
                                $q4->forceDelete();
                            });
                            $q3->Tags()->onlyTrashed()->each(function ($q4) {
                                $q4->forceDelete();
                            });
                            $q3->GradeOverview()->onlyTrashed()->each(function ($q4) {
                                $q4->forceDelete();
                            });

                            $q3->forceDelete();
                        });
                        $q2->Tags()->onlyTrashed()->each(function ($q3) {
                            $q3->forceDelete();
                        });
                        $q2->QuickScores()->onlyTrashed()->each(function ($q3) {
                            $q3->forceDelete();
                        });
                        $q2->ActiveTimer()->onlyTrashed()->each(function ($q3) {
                            $q3->forceDelete();
                        });
                        $q2->Like()->onlyTrashed()->each(function ($q3) {
                            $q3->forceDelete();
                        });

                        $q2->forceDelete();
                    });
                    $q1->Like()->onlyTrashed()->each(function ($q2) {
                        $q2->forceDelete();
                    });
                    $q1->PremiumUserAccess()->onlyTrashed()->each(function ($q2) {
                        $q2->forceDelete();
                    });
                    $q1->Report()->onlyTrashed()->each(function ($q2) {
                        $q2->forceDelete();
                    });

                    $q1->forceDelete();
                });
                $restoreUserRelations->Comment()->onlyTrashed()->each(function ($q1) {
                    $q1->Like()->onlyTrashed()->each(function ($q2) {
                        $q2->forceDelete();
                    });

                    $q1->forceDelete();
                });
                $restoreUserRelations->ResponseComment()->onlyTrashed()->each(function ($q1) {
                    $q1->Like()->onlyTrashed()->each(function ($q2) {
                        $q2->forceDelete();
                    });

                    $q1->forceDelete();
                });
                $restoreUserRelations->Point()->onlyTrashed()->each(function ($q1) {
                    $q1->forceDelete();
                });
                $restoreUserRelations->Like()->onlyTrashed()->each(function ($q1) {
                    $q1->forceDelete();
                });
                $restoreUserRelations->Response()->onlyTrashed()->each(function ($q1) {
                    $q1->ResponseContent()->onlyTrashed()->each(function ($q2) {
                        $q2->forceDelete();
                    });
                    $q1->Like()->onlyTrashed()->each(function ($q2) {
                        $q2->forceDelete();
                    });
                    $q1->Comment()->onlyTrashed()->each(function ($q2) {
                        $q2->Like()->onlyTrashed()->each(function ($q3) {
                            $q3->forceDelete();
                        });

                        $q2->forceDelete();
                    });
                    $q1->Report()->onlyTrashed()->each(function ($q2) {
                        $q2->forceDelete();
                    });
                    $q1->Tags()->onlyTrashed()->each(function ($q2) {
                        $q2->forceDelete();
                    });
                    $q1->GradeOverview()->onlyTrashed()->each(function ($q2) {
                        $q2->forceDelete();
                    });

                    $q1->forceDelete();
                });
                $restoreUserRelations->PremiumClassRoomAccess()->onlyTrashed()->each(function ($q1) {
                    $q1->forceDelete();
                });
                $restoreUserRelations->UserReporting()->onlyTrashed()->each(function ($q1) {
                    $q1->forceDelete();
                });
                $restoreUserRelations->UserReported()->onlyTrashed()->each(function ($q1) {
                    $q1->forceDelete();
                });
                $restoreUserRelations->Notifications()->onlyTrashed()->each(function ($q1) {
                    $q1->forceDelete();
                });
                $restoreUserRelations->NotificationsFrom()->onlyTrashed()->each(function ($q1) {
                    $q1->forceDelete();
                });
                $restoreUserRelations->Followed()->onlyTrashed()->each(function ($q1) {
                    $q1->forceDelete();
                });
                $restoreUserRelations->Follower()->onlyTrashed()->each(function ($q1) {
                    $q1->forceDelete();
                });
                $restoreUserRelations->BlockedTo()->onlyTrashed()->each(function ($q1) {
                    $q1->forceDelete();
                });
                $restoreUserRelations->BlockedFrom()->onlyTrashed()->each(function ($q1) {
                    $q1->forceDelete();
                });
                $restoreUserRelations->ContentReports()->onlyTrashed()->each(function ($q1) {
                    $q1->forceDelete();
                });

                $restoreUser = User::onlyTrashed()->where('deleted_at', '<', Carbon::now()->subDays(30))->forceDelete();
                echo "\r\n[" . Carbon::now() . "] " . "Account Deleted: " . $restoreUser;
            } else {
                echo "\r\n[" . Carbon::now() . "] " . "No Account Deleted";
            }

            return 0;
        } catch (\exception $exception) {
            return 0;
        }
    }
}
